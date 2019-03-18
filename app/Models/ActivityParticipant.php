<?php

namespace App\Models;

class ActivityParticipant extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

}
