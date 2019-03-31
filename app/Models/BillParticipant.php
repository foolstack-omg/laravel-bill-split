<?php

namespace App\Models;

class BillParticipant extends Model
{
    protected $fillable = [
        'bill_id', 'user_id', 'split_money', 'fixed', 'paid'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
