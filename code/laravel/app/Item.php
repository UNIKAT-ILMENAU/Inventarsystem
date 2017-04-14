<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type', 'state', 'place_id', 'category_id', 'description', 'deleted', 'visible',
        'storage_value', 'critical_storage_value', 'uom', 'uom_short', 'build_type', 'sale_price'];

    public static function all($columns = ['*'])
    {
        $items = Item::with(['category', 'place'])->get();
        return $items;
    }

    public static function getAllPublic() {
        $items = Item::with('category')
            ->select('id', 'name', 'type', 'state', 'storage_value', 'sale_price', 'category_id')
            ->get();
        return $items;
    }

    public function allData() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'state' => $this->state,
            'storage_value' => $this->storage_value,
            'uom' => $this->uom,
            'uom_short' => $this->uom_short,
            'sale_price' => $this->sale_price,
            'place' => ($this->place != null) ? $this->place->getFullTree() : null,
            'category' => ($this->category != null) ? $this->category->getFullTree() : null
        );
    }




    public function publicData() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'state' => $this->state,
            'storage_value' => $this->storage_value,
            'uom' => $this->uom,
            'uom_short' => $this->uom_short,
            'sale_price' => $this->sale_price,
//            'place' => $this->place->name,
            'category' => ($this->category != null) ? $this->category->getFullTree() : null
        );
    }
    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function history() {
        return $this->hasMany(History::class);
    }
}
