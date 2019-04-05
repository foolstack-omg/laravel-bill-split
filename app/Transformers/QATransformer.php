<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-04-05
 * Time: 14:19
 */

namespace App\Transformers;


use App\Models\QA;
use League\Fractal\TransformerAbstract;

class QATransformer extends TransformerAbstract
{
    public function transform(QA $qa) {
        return [
            'question' => $qa->question,
            'answer' => $qa->answer
        ];
    }
}
