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
use PDF;

class PdfController extends Controller
{
    public function pdf() {
        $pdf = PDF::loadView('pdf', [])->setOptions([
            'orientation' => 'Landscape'
        ]); //pdf.invoice是你的blade模板
        return $pdf->download(date('Y-m-d-', time()).str_random(4).'.pdf');
    }

    public function view() {
        return view('pdf');
    }
}
