<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $casts = [
        'data' => 'json'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function getDataAttribute($value) {
        return json_decode($value);
    }

    public function setTypeCreated($comment) {
        $this->entry_type = 1;
        $this->data = array('comment' => $comment);
    }

    public function setTypeStateChanged($comment, $oldState, $newState) {
        $this->entry_type = 2;
        $this->data = array('comment' => $comment, 'old_state' => $oldState, 'new_state' => $newState);
    }

}
