<?php

namespace App\Models;

use Illuminate\Support\Str;
use URL;

class Activity extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function participatedUsers() {
        return $this->belongsToMany(User::class, 'activity_participants', 'activity_id', 'user_id')
            ->withTimestamps();
    }

    public function bills() {
        return $this->hasMany(Bill::class);
    }

    public function getWxACodeAttribute(){
        if($this->attributes['wx_a_code']){
            // 如果 image 字段本身就已经是完整的 url 就直接返回
            if (Str::startsWith($this->attributes['wx_a_code'], ['http://', 'https://'])) {
                return $this->attributes['wx_a_code'];
            }
            return \Storage::disk('public')->url($this->attributes['wx_a_code']);
        }else{
            return $this->attributes['wx_a_code'];
        }

    }

}
