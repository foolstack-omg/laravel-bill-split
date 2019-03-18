<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 17:20
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Activities\SaveRequest;
use App\Http\Transformers\ActivityTransformer;
use App\Models\Activity;
use App\Models\User;
use App\Transformers\ActivityParticipantTransformer;
use App\Transformers\UserTransformer;

class ActivitiesController extends Controller
{

    public function save(SaveRequest $request, Activity $activity = null) {
        if(empty($activity)){
            $activity = new Activity();
            $activity->user_id = $this->user()->id;
        }
        $activity->name = $request->name;
        $activity->save();

        return $this->response->accepted();
    }

    public function myActivities() {
        $user =$this->user();
        $my_activities = $this->user()
            ->activities()
            ->withCount([
                'bills.participants as split_sum' => function ($query) use ($user){
                    $query->where('user_id', $user->id)
                        ->selectRaw("sum(split_money) AS split_sum");
                },
                'bills.participants as unpaid_sum' => function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('paid', 0)
                        ->selectRaw("sum(split_money) AS unpaid_sum");
                }
            ])
            ->get();

        return $this->response->collection($my_activities, new ActivityTransformer());
    }


    public function participants(Activity $activity) {
        return $this->response->collection($activity->participants()->with('user')->get(), new ActivityParticipantTransformer());
    }
}
