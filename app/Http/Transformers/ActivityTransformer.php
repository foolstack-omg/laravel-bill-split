<?php
/**
 * Created by PhpStorm.
 * User: liuxiaofeng
 * Date: 2019-03-16
 * Time: 17:50
 */

namespace App\Http\Transformers;


use App\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{

    public function transform(Activity $activity) {
        return [
            'id' => $activity->id,
        ];
    }
}
