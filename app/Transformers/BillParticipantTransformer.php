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
use App\Models\BillParticipant;
use League\Fractal\TransformerAbstract;

class BillParticipantTransformer extends TransformerAbstract
{
    public $availableIncludes = [
        'user'
    ];

    public function transform(BillParticipant $model) {
        $data = [
            'id' => $model->id,
            'bill_id' => $model->bill_id,
            'user_id' => $model->user_id,
            'split_money' => $model->split_money,
            'fixed' => $model->fixed,
            'paid' => $model->paid
        ];

        return $data;
    }

    public function includeUser(BillParticipant $model) {
        return $this->item($model->user, new UserTransformer());
    }



}
