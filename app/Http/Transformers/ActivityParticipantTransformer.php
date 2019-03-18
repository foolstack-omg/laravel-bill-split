<?php

namespace App\Transformers;

use App\Models\ActivityParticipant;
use League\Fractal\TransformerAbstract;

class ActivityParticipantTransformer extends TransformerAbstract
{

    public function transform(ActivityParticipant $participant)
    {
        return [
            'id' => $participant->id,
            'name' => $participant->user->name ?? '',
            'avatar_url' => $participant->user->avatar_url,
            'created_at' => $participant->created_at->toDateTimeString(),
            'updated_at' => $participant->updated_at->toDateTimeString(),
        ];
    }
}
