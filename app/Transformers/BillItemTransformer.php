<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 17:50
 */

namespace App\Transformers;


use App\Models\Activity;
use App\Models\Bill;
use App\Models\BillItem;
use League\Fractal\TransformerAbstract;

class BillItemTransformer extends TransformerAbstract
{
    public function transform(BillItem $item) {
        $data = [
            'id' => $item->id,
            'bill_id' => $item->bill_id,
            'title' => $item->title,
            'money' => $item->money,
        ];

        return $data;
    }

}
