<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-04-05
 * Time: 13:46
 */

namespace App\Http\Controllers\Api;

use App\Models\QA;
use App\Transformers\QATransformer;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function pdf(Request $request) {
        $data = json_decode(urldecode($request->input('data')), true);

//        $data = [
//            'title' => '世茂广场',
//            'subtitle' => '上画监测报告',
//            'ads' => [
//                [
//                    'images' => ['https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3039140561,11452166&fm=26&gp=0.jpg', 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3039140561,11452166&fm=26&gp=0.jpg'],
//                    'position1' => '建南路',
//                    'position2' => '宁宝花园西门站(东)',
//                    'code' => 'hct179'
//                ],
//                [
//                    'images' => ['https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3039140561,11452166&fm=26&gp=0.jpg', 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3039140561,11452166&fm=26&gp=0.jpg'],
//                    'position1' => '石鼓路',
//                    'position2' => '银亭社区站',
//                    'code' => 'hct285'
//                ]
//            ]
//        ];

        $pdf = PDF::loadView('pdf', ['data' => $data])->setOptions([
//            'orientation' => 'Landscape',
            'margin-bottom' => 0,
            'margin-left' => 0,
            'margin-right' => 0,
            'margin-top' => 0,//210 x 297
            'page-height' => 152,
            'page-width' => 273,
            'disable-smart-shrinking' => true
        ]); //pdf.invoice是你的blade模板
        return $pdf->download($data['title'].'-'.$data['subtitle'].'-'.date('Ymd-', time()).str_random(4).'.pdf');
    }

    public function view(Request $request) {
        $data = json_decode(urldecode($request->input('data')), true);

        return view('pdf', ['data' => $data]);
    }
}
