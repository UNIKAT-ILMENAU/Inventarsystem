<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    public function parent() {
        return $this->belongsTo(Category::class);
    }

    public function children() {
        return $this->hasMany(Category::class, 'parent');
    }

    public function getFullTree() {
        if($this->parent != null) {
            $parent = Category::find($this->parent);
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
