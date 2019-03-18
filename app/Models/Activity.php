<?php

namespace App\Models;

class Activity extends Model
{
    public function participants() {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }


}
