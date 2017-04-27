<?php

namespace App\Http\Controllers;

use App\Borrower;
use App\Item;
use App\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Rental::with(['items', 'borrower'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $borrower = new Borrower;
        $borrower->name = $request->name;
        $borrower->email = $request->email;
        $borrower->phone = $request->phone;
        $borrower->save();

        $rental = new Rental;
        $rental->borrower()->associate($borrower);
        $rental->end_date = $request->enddate;
        $rental->user_id = Auth::user()->id;
        $rental->save();

        $items = $request->items;
        // add items to rental
        foreach($items as $item_json) {
            $item = Item::findOrFail($item_json['id']);

            // check if item is available
            if($item->isDevice()) {
                $amount = 1;
                if(!$item->isAvailable()) {
                    return response()->error('Item not available. ItemId: ' . $item->id);
                }
            } else if ($item->isMaterial()) {
                $amount = $item_json['amount'];
                if(!$item->isAvailable($amount)) {
                    return response()->error('Item not available. ItemId: ' . $item->id);
                }
            }

            $item->rent($amount);
            $rental->items()->attach($item, ['amount' => $amount]);

        }

        return response()->success('Rented successful');
    }


    public function returnItem(Rental $rental, Request $request) {
        $item_id = $request->itemid;
        $item_entry = $rental->items->find($item_id);

        $rental->returnItem($item_entry, $request->amount);

        $item_entry->bringBack($request->amount);

        // TODO add history
    }

    public function lostItem(Rental $rental, Request $request) {
        $item_id = $request->itemid;
        $item_entry = $rental->items->find($item_id);

        $rental->returnItem($item_entry, $request->amount);

        $item_entry->lost($request->amount);

        // TODO add history
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function show(Rental $rental)
    {
        return $rental;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

}
