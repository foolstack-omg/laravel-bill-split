<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 17:50
 */

namespace App\Transformers;


use App\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    public $availableIncludes = [
        'participatedUsers', 'user'
    ];

    public function transform(Activity $activity) {
        return [
            'id' => $activity->id,
            'user_id' => $activity->user_id,
            'created_at' => $activity->created_at->format('Y/m/d'),
            'name' => $activity->name,
            'wx_a_code' => $activity->wx_a_code,
            'split_sum' => $activity->split_sum ?? null,
            'unpaid_sum' => $activity->unpaid_sum ?? null,
            'all_unpaid_sum' => $activity->all_unpaid_sum ?? null,
        ];
    }

    public function includeUser(Activity $activity) {
        return $this->item($activity->user, new UserTransformer());
    }

    public function includeParticipatedUsers(Activity $activity)
    {
        return $this->collection($activity->participatedUsers()->orderBy('created_at','desc')->get(), new UserTransformer());
    }


}
