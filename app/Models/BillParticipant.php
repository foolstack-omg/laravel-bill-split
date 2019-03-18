<?php

namespace App\Models;

class BillParticipant extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}
