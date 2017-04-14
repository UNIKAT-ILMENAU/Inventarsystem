<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\Place;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get() {
        $data = [];
//dd(Item::where('visible', 1)->get());
        $data['items'] = Item::all()->count();
        $data['items-not-available'] = Item::where('state', '0')->count();
        $data['items-visible'] = Item::where('visible', 1)->count();
        $data['items-invisible'] = Item::where('visible', 0)->count();
        $data['missing-devices'] = Item::where('type', 'DEVICE')->where('state', 3)->count();
        $data['devices'] = Item::where('type', "DEVICE")->count();
        $data['materials'] = Item::where('type', "MATERIAL")->count();
        $data['admins'] = User::all()->count();
        $data['places'] = Place::all()->count();
        $data['categories'] = Category::all()->count();

        return $data;
    }
}
