<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getDataAttribute($value) {
        return json_decode($value);
    }

}
