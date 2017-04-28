<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicIndex(Request $request)
    {
        $items = \App\Item::getAllPublicQuery();

        if($request->search) {
            $items = $items
                ->where('items.name', 'like', "%$request->search%")
                ->orWhere('items.description', 'like', "%$request->search%")
                ->orWhere('categories.name', 'like', "%$request->search%");
        }

        if($request->orderBy && $request->reverse) {
            $order = $request->reverse == 'true' ? 'desc' : 'asc';
            $items = $items->orderBy($request->orderBy, $order);
        }

        $items = $items->paginate(5);

        return $items;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = \App\Item::query();

        $items = $items->join('categories', 'items.category_id', '=', 'categories.id');

        if($request->search) {
            $items = $items
                ->where('items.name', 'like', "%$request->search%")
                ->orWhere('items.description', 'like', "%$request->search%")
                ->orWhere('categories.name', 'like', "%$request->search%");
        }

        if($request->orderBy && $request->reverse) {
            $order = $request->reverse == 'true' ? 'desc' : 'asc';
            $items = $items->orderBy($request->orderBy, $order);
        }

        $items = $items->paginate(5, ['items.*']);

        return $items;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::create($request->all());
        $item->save();

        HistoryController::addCreatedEntry($item, $request->comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function publicShow(Item $item)
    {
        return $item->publicData();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        $resp = $item;
        $resp['place'] = $item->place;
        $resp['category'] = $item->category;

        return $resp;
    }

    public function history(Item $item)
    {
        return $item->history->each->user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
        // TODO find changes, add history
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }

    public function defective(Request $request, Item $item) {
        if($item->type == "MATERIAL") {
            return response()->error('Not allowed on materials');
        }

        $old_state = $item->state;
        $item->setStateToDefective($request->comment);
        $item->save();

        HistoryController::addStateChangedEntry($item, $request->comment, $old_state, $item->state);
    }

    public function missing(Request $request, Item $item) {
        if($item->type == "MATERIAL") {
            return response()->error('Not allowed on materials');
        }

        $old_state = $item->state;
        $item->setStateToMissing($request->comment);
        $item->save();

        HistoryController::addStateChangedEntry($item, $request->comment, $old_state, $item->state);
    }

    public function available(Request $request, Item $item) {
        if($item->type == "MATERIAL") {
            return response()->error('Not allowed on materials');
        }

        $old_state = $item->state;
        $item->setStateToAvailable($request->comment);
        $item->save();

        HistoryController::addStateChangedEntry($item, $request->comment, $old_state, $item->state);
    }
}
