<?php

namespace App;

use App\Http\Controllers\HistoryController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'type', 'state', 'place_id', 'category_id', 'description', 'deleted', 'visible',
        'storage_value', 'critical_storage_value', 'uom', 'uom_short', 'build_type', 'sale_price'];

    protected $with = ['place', 'category'];

    public function place() {
        return $this->belongsTo(Place::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function history() {
        return $this->hasMany(History::class);
    }

    public static function all($columns = ['*'])
    {
        $items = Item::with(['category', 'place'])->get();
        return $items;
    }

    public static function getAllPublicQuery() {
        $items = Item::with('category')
            ->select('items.id', 'items.name', 'type', 'state', 'storage_value', 'sale_price', 'category_id')
            ->join('categories', 'items.category_id', '=', 'categories.id');
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

    public function setStateToNotAvailable() {
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
                $this->setStateToNotAvailable();
            }
        } else if($this->isDevice()) {
            $this->setStateToRented();
        }

        $this->save();
        // todo add history?
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

    public function useMaterial($amount) {
        if(! ($this->storage_value - $amount >= 0)) {
            return false;
        }

        $this->storage_value -= $amount;

        $this->save();

        return true;
    }

    public function restock($amount) {
        $this->storage_value += $amount;
        $this->save();
    }


}
