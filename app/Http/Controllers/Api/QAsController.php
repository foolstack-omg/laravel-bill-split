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

class QAsController extends Controller
{
    public function index() {
        return $this->response()->collection(QA::all(), new QATransformer());
    }
}
