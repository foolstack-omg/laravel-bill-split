<?php

namespace App\Models;

class Bill extends Model
{
    public function items() {
        return $this->hasMany(BillItem::class);
    }

    public function participants() {
        return $this->hasMany(BillParticipant::class);
    }

    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}
