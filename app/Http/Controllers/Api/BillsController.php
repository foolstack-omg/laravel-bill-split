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
                $query->where('user_id', $this->user->id);
            }])
            ->orderBy('created_at', 'desc')->get();

        $bills->each(function($item) {
            if($item->participants->isNotEmpty()){
                $item->split_money = $item->participants[0]->split_money;
                $item->unpaid_money = $item->participants[0]->paid ? 0.00 : $item->split_money;
            }else{
                $item->split_money = 0;
                $item->unpaid_money = 0;
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
}
