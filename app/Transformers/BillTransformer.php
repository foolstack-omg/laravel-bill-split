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
use League\Fractal\TransformerAbstract;

class BillTransformer extends TransformerAbstract
{
    public $availableIncludes = [
        'user', 'items', 'participants'
    ];

    public function transform(Bill $bill) {
        $data = [
            'id' => $bill->id,
            'user_id' => $bill->user_id,
            'created_at' => $bill->created_at->format('Y/m/d H:i'),
            'activity_id' => $bill->activity_id,
            'title' => $bill->title,
            'description' => $bill->description,
            'money' => $bill->money,
        ];
        if(isset($bill->split_money)){
            $data['split_money'] = $bill->split_money;
        }
        if(isset($bill->unpaid_money)){
            $data['unpaid_money'] = $bill->unpaid_money;
        }
        if(isset($bill->all_unpaid_money)){
            $data['all_unpaid_money'] = $bill->all_unpaid_money;
        }
        return $data;
    }

    public function includeUser(Bill $bill) {
        return $this->item($bill->user, new UserTransformer());
    }

    public function includeItems(Bill $bill) {
        return $this->collection($bill->items, new BillItemTransformer());
    }

    public function includeParticipants(Bill $bill) {
        return $this->collection($bill->participants()->orderBy('id', 'desc')->get(), new BillParticipantTransformer());
    }



}
