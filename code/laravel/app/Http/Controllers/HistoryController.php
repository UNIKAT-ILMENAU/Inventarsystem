<?php

namespace App\Http\Controllers;

use App\History;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{

    public static function addCreatedEntry(Item $item, $comment) {
        $history = new History();
        $history->user()->associate(Auth::user());
        $history->item()->associate($item);
        $history->setTypeCreated($comment);
        $history->save();
    }

    public static function addStateChangedEntry(Item $item, $comment, $oldState, $newState) {
        $history = new History();
        $history->user()->associate(Auth::user());
        $history->item()->associate($item);
        $history->setTypeStateChanged($comment, $oldState, $newState);
        $history->save();
    }

    public static function addMaterialUsedEntry(Item $item, $comment, $amount) {
        $history = new History();
        $history->user()->associate(Auth::user());
        $history->item()->associate($item);
        $history->setTypeMaterialUsed($comment, $amount);
        $history->save();
    }

    public static function addMaterialRestockedEntry(Item $item, $comment, $amount) {
        $history = new History();
        $history->user()->associate(Auth::user());
        $history->item()->associate($item);
        $history->setTypeMaterialRestocked($comment, $amount);
        $history->save();
    }
}
