<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 21:28
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Bills\SaveRequest;
use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\BillParticipant;
use App\Transformers\BillTransformer;
use Carbon\Carbon;
use DB;
use Image;

class BillsController extends Controller
{
    public function bills(Activity $activity) {
        $bills = $activity->bills()
            ->where(function($query) {
                $query
                    ->where('user_id', $this->user->id)
                    ->orWhereHas('participants', function($query){
                        $query->where('user_id', $this->user->id);
                    });
            })
            ->with(['participants' => function ($query) {
                //$query->where('user_id', $this->user->id);
            }])
            ->orderBy('created_at', 'desc')->get();

        $bills->each(function($item) {
            $participants_of_me = $item->participants->filter(function($participant) {
                return $participant->user_id === $this->user->id;
            });

            if($participants_of_me->isNotEmpty()){
                $item->split_money = $participants_of_me->first()->split_money;
                $item->unpaid_money = $participants_of_me->first()->paid ? 0.00 : $item->split_money;
            }else{
                $item->split_money = 0;
                $item->unpaid_money = 0;
            }

            if ($item->user_id === $this->user->id) {
                $all_unpaid_money = 0.00;
                $item->participants->each(function($participant) use (&$all_unpaid_money) {
                    if($participant->user_id !== $this->user->id && !$participant->paid) {
                        $all_unpaid_money = bcadd($all_unpaid_money, $participant->split_money, 2);
                    }
                });
                $item->all_unpaid_money = $all_unpaid_money;
            }



        });

        return $this->response->collection($bills, new BillTransformer());
    }

    public function show(Bill $bill) {
        return $this->response->item($bill, new BillTransformer());
    }

    public function save(SaveRequest $request, Activity $activity, Bill $bill = null) {
        $participants_collection = collect($request->input('participants.data'));
        if(bccomp($request->money, $participants_collection->sum('split_money'), 2) !== 0){
            return $this->response->errorBadRequest();
        }

        DB::transaction(function() use ($request, $activity, $bill, $participants_collection) {
            if(empty($bill)){
                $bill = new Bill();
                $bill->activity_id = $activity->id;
                $bill->user_id = $this->user()->id;
            }else{
                $this->authorize('update', $bill);
            }

            $bill->title = $request->title ?? Carbon::now()->format('Y-m-d H:i');
            $bill->description = $request->description ?? '';
            $bill->money = $request->money;
            $bill->save();

            $bill_items_collection = collect($request->input('items.data'));
            $bill_item_ids = $bill_items_collection->pluck('id')->filter(function($v) {
                return $v !== null;
            });

            BillItem::query()->where('bill_id', $bill->id)
                ->whereNotIn('id', $bill_item_ids)
                ->delete();

            $bill_items_collection->each(function($v) use ($bill){
                $v['bill_id'] = $bill->id;
                BillItem::query()->updateOrCreate(['id' => $v['id'] ?? null], $v);
            });

            BillParticipant::query()->where('bill_id', $bill->id)
                ->whereNotIn('id', $participants_collection->pluck('id')->filter(function($v) {
                    return $v !== null;
                }))
                ->delete();

            $fixed_participants = $participants_collection->filter(function($v) {
                return $v['fixed'];
            });
            $not_fixed_participants = $participants_collection->filter(function($v) {
                return !$v['fixed'];
            });

            $fixed_money = $fixed_participants->sum('split_money');

            $remain_money = bcsub($bill->money, $fixed_money, 2);
            $split_money = 0;
            $last_one_split_money = 0;
            if($not_fixed_participants->count() > 0) {
                $split_money = bcdiv($remain_money, $not_fixed_participants->count(), 2);
                $last_one_split_money = bcsub($remain_money, bcmul($split_money, $not_fixed_participants->count() - 1, 2), 2);
            }

            $participants_collection->each(function($v, $k) use ($bill, $split_money, &$remain_money, $last_one_split_money){
                $v['bill_id'] = $bill->id;
                if(!$v['fixed']) {
                    if($remain_money === $last_one_split_money) {
                        $v['split_money'] = $last_one_split_money;
                    }else{
                        $v['split_money'] = $split_money;
                    }
                    $remain_money = bcsub($remain_money, $v['split_money'], 2);
                }

                BillParticipant::query()->updateOrCreate(
                    ['user_id' => $v['user_id'], 'bill_id' => $bill->id],
                    $v
                );
            });


        });

        return $this->response->noContent();

    }

    public function delete(Bill $bill) {
        if(!$this->user()->isAuthorOf($bill)){
            return $this->response->errorUnauthorized();
        }

        DB::transaction(function() use ($bill) {
            $bill->items()->delete();
            $bill->participants()->delete();
            $bill->delete();
        });

        return $this->response->noContent();

    }

    /**
     * 生成海报
     * @param Bill $bill
     * @return mixed|void
     */
    public function poster(Bill $bill) {
        if(!$this->user()->isAuthorOf($bill)){
            return $this->response->errorUnauthorized();
        }
        function _circleImg($imgPath)
        {
            try {
                $src_img = @imagecreatefromjpeg($imgPath);
            }catch (\Exception $e) {
                try {
                    $src_img = imagecreatefrompng($imgPath);
                }catch (\Exception $e) {
                    $src_img = null;
                }

            }
            if($src_img === null) {
                return null;
            }


            list($w, $h) = getimagesize($imgPath);
            $w           = $h = min($w, $h);
            $img         = imagecreatetruecolor($w, $h);
            imagesavealpha($img, true);

            // 拾取一个完全透明的颜色,最后一个参数127为全透明
            $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);

            imagefill($img, 0, 0, $bg);
            $r = $w / 2; // 圆的半径
            for ($x = 0; $x < $w; $x++) {
                for ($y = 0; $y < $h; $y++) {
                    $rgbColor = imagecolorat($src_img, $x, $y);
                    if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r)))
                        imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
            imagedestroy($src_img);
            return $img;
        }

        $top = Image::make(public_path('images/poster/top.png'));

        $bottom = Image::make(public_path('images/poster/bottom.png'));

        $bill = Bill::query()->with(['participants' => function($query) {
            $query->where('paid', 0);
        }, 'participants.user', 'user'])->findOrFail($bill->id);


        /**
         * 中部账单内容部分
         */
        $content = Image::canvas(800, 94 + 24 + count($bill->participants) * 257 + 130);
        $content->fill(public_path('images/poster/middle.png'));
        $content->rectangle(71, 2, 729, 94, function($draw) {
            $draw->background('#FFFFFF');
            $draw->border(3, '#482E80');
        });
        // 账单标题
        $content->text('- '.$bill->title.' -', 400, 47, function ($font) {
            $font->file(public_path('fonts/PingFang-Bold.ttf'));
            $font->size(42);
            $font->color('#3C2648');
            $font->align('center');
            $font->valign('center');
        });
        $content_cur_y = 94; // 内容部分当前垂直方向距离
        $margin_bottom = 24;
        $content_cur_y += $margin_bottom;


        // 账单未付款成员
        foreach ($bill->participants as $k => $participant) {
            $content->rectangle(71, $content_cur_y, 729, $content_cur_y + 233, function($draw) {
                $draw->background('#FFFFFF');
                $draw->border(3, '#482E80');
            });

            $portrait_url = $participant->user->avatar_url;
            if(@file_get_contents($portrait_url, null, null, 0, 1)){
                $portrait = _circleImg($portrait_url);
                if($portrait === null) {
                    $portrait = Image::canvas(78, 78);
                    $portrait->circle(78, 39, 39, function($draw) {
                        $draw->background('#F6F5F9');
                    });
                } else {
                    $portrait = Image::make($portrait);
                    $portrait->resize(78, 78);
                }
            } else {
                $portrait = Image::canvas(78, 78);
                $portrait->circle(78, 39, 39, function($draw) {
                    $draw->background('#F6F5F9');
                });
            }

            $content->insert($portrait, 'top-left', 71 + 29, $content_cur_y + 25);

            $content->text($participant->user->name, 71 + 133, $content_cur_y + 44, function ($font) {
                $font->file(public_path('fonts/PingFang-Bold.ttf'));
                $font->size(36);
                $font->color('#3C2648');
                $font->align('left');
                $font->valign('top');
            });

            $content->circle(60, 71 + 567 + 30, $content_cur_y + 30 + 30, function ($draw) {
                $draw->background('#FFE02E');
            });

            $index = sprintf('%02d', $k+1);
            $content->text($index, 71 + 567 + 30 + 2, $content_cur_y + 30 + 30, function ($font) {
                $font->file(public_path('fonts/PingFang-Bold.ttf'));
                $font->size(28);
                $font->color('#3C2648');
                $font->align('center');
                $font->valign('center');
            });

            $content->rectangle(71 + 24, $content_cur_y + 124, 71 + 24 + 610, $content_cur_y + 124 + 84, function($draw) {
                $draw->background('#F6F5F9');
            });
            $content->text('需支付', 71 + 24 + 35, $content_cur_y + 124 + 26 + 16, function ($font) {
                $font->file(public_path('fonts/PingFang-Bold.ttf'));
                $font->size(32);
                $font->color('#3C2648');
                $font->align('left');
                $font->valign('middle');
            });
            $content->text($participant->split_money, 71 + 24 + 610 - 35 , $content_cur_y + 124 + 26 + 16, function ($font) {
                $font->file(public_path('fonts/PingFang-Bold.ttf'));
                $font->size(32);
                $font->color('#3C2648');
                $font->align('right');
                $font->valign('middle');
            });


            $content_cur_y += 233 + $margin_bottom;
        }



        // 账单底部提示
        $content->rectangle(71, $content_cur_y, 729, $content_cur_y + 128, function($draw) {
            $draw->background('#FFFFFF');
            $draw->border(3, '#482E80');
        });

        $content->text("请以上成员尽快转账给{$bill->user->name}，", 97 + 298, $content_cur_y + 30, function ($font) {
            $font->file(public_path('fonts/PingFang-Bold.ttf'));
            $font->size(26);
            $font->color('#3C2648');
            $font->align('center');
            $font->valign('top');
        });
        $content->text('否则将会遭受兔大人的小拳拳伺候！', 97 + 298, $content_cur_y + 75, function ($font) {
            $font->file(public_path('fonts/PingFang-Bold.ttf'));
            $font->size(26);
            $font->color('#3C2648');
            $font->align('center');
            $font->valign('top');
        });


        /**
         * 底部二维码部分
         */
        $bottom->text('未来会有收款码功能', 400, 208, function($font) {
            $font->file(public_path('fonts/PingFang-Bold.ttf'));
            $font->size(32);
            $font->color('#3C2648');
            $font->align('center');
            $font->valign('top');
        });
        $bottom->text('敬请期待', 400, 308, function($font) {
            $font->file(public_path('fonts/PingFang-Bold.ttf'));
            $font->size(68);
            $font->color('#3C2648');
            $font->align('center');
            $font->valign('top');
        });


        /**
         * 最终插入所有部分图片形成海报
         */
        $canvas = Image::canvas(800, $top->height() + $content->height() + $bottom->height());
        $canvas->insert($top, 'top-left', 0, 0);
        $canvas->insert($content, 'top-left', 0, $top->height());
        $canvas->insert($bottom, 'top-left', 0, $top->height() + $content->height());


        return $canvas->response('png');

    }
}
