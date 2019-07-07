<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

function _circleImg($imgPath)
{
    $src_img = imagecreatefromjpeg($imgPath);

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
Route::get('/poster', function () {

    $top = Image::make(public_path('images/poster/top.png'));

    $bottom = Image::make(public_path('images/poster/bottom.png'));

    $bill = \App\Models\Bill::query()->with(['participants' => function($query) {
        $query->where('paid', 0);
    }, 'participants.user', 'user'])->findOrFail(28);


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
            $portrait = Image::make($portrait);
            $portrait->resize(78, 78);
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
});
