<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type', 'state', 'place_id', 'category_id', 'description', 'deleted', 'visible',
        'storage_value', 'critical_storage_value', 'uom', 'uom_short', 'build_type', 'sale_price'];

    protected $with = ['place', 'category'];

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

    public function isDevice() {
        if($this->type == 'DEVICE') {
            return true;
        } else {
            return false;
        }
    }

    public function isMaterial() {
        if($this->type == 'MATERIAL') {
            return true;
        } else {
            return false;
        }
    }

    public function isAvailable($amount = 1) {
        if($this->isDevice()) {
            if($this->state == 1) {
                return true;
            } else {
                return false;
            }
        } else if($this->isMaterial()) {
            if($this->storage_value - $amount >= 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function setStateToNoAvailable() {
        $this->state = 0;
    }

    public function setStateToAvailable() {
        $this->state = 1;
    }

    public function setStateToDefective() {
        $this->state = 2;
    }

    public function setStateToMissing() {
        $this->state = 3;
    }

    public function setStateToRented() {
        $this->state = 4;
    }


    public function rent($amount) {
        if($this->isMaterial()) {
            $this->storage_value -= $amount;
            if($this->storage_value == 0) {
                $this->setStateToNoAvailable();
            }
        } else if($this->isDevice()) {
            $this->setStateToRented();
        }

        $this->save();

    }

    public function bringBack($amount) {

        if($this->isDevice()) {
            $this->setStateToAvailable();
            $this->storage_value = 1;
        } else if($this->isMaterial()) {
            $this->storage_value += $amount;
            if($this->storage_value > 0) {
                $this->setStateToAvailable();
            }
        }

        $this->save();
        //todo add history to item
    }

    public function lost($amount) {

        if($this->isDevice()) {
            $this->setStateToMissing();
        }

        $this->save();

        //todo add history to item
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
