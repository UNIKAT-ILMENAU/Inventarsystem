<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rental extends Model
{
    protected $appends = array('completed');

    protected $with = ['items', 'borrower'];

    public function borrower() {
        return $this->belongsTo(Borrower::class);
    }

    public function items() {
        return $this->belongsToMany(Item::class)->withPivot('amount', 'returned');
    }

    public function getCompletedAttribute() {

        foreach($this->items()->get() as $item) {
//            dump($item->name, $item->pivot->amount, $item->pivot->returned);

            if($item->pivot->amount > $item->pivot->returned) {

                return "false";
            }
        }

        return "true";

    }

    public function returnItem($item, $amount) {
        // cast amount to int to prevent sql injections in raw query
        $amount = (int) $amount;

        $this->items()->updateExistingPivot($item->id,
            ['returned' => DB::raw("`returned` + $amount")]
        );
    }
}
