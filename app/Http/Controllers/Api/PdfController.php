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
        return PDF::loadFile('http://www.baidu.com')->inline('github.pdf');
    }
}
