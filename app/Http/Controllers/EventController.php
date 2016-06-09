<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Http\Requests;

class EventController extends Controller
{
    public function showAllEvents()
     {
          
        return DB::table('event')
        ->join('user', 'user.id', '=', 'event.CreatedByID')
        ->select('event.id as ID','event.Name as Name', 'event.Description as Description', 'event.EventValue as EventValue'	,'event.CreatedByID as CreatedByID')
        ->orderBy('event.id')
        ->get();
    }



    
}
