<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    public $timestamps = false;

    public function parent() {
        return $this->belongsTo(Place::class);
    }

    public function children() {
        return $this->hasMany(Place::class, 'parent');
    }

    public function getFullTree() {
        if($this->parent != null) {
            $parent = Place::find($this->parent);
            $data = $this;
            $data['parent'] = $parent->getFullTree();
            return $data;
        } else {
            return $this;
        }
    }

    public function getChildTree() {
        if($this->children != null) {
            return $this->children->each->getChildTree();
        } else {
            return $this;
        }
    }
}
