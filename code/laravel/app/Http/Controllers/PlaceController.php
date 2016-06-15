<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;


use App\Http\Requests;

class PlaceController extends Controller
{

    public function showAllPlace()
    { 
        return DB::table('place')
            ->select('id', 'Name')
            ->get();
    }

    public function getPlace($id)
    {
        return DB::table('place')
            ->where('id',$id)
            ->select('Name', 'CreatedByID', 'BeforeID','created_at', 'updated_at')
            ->get();

    }


    public function PlaceStore(Request $request)
    {   
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now();
        
        
        /*
            'Name'=> 'Room X',
            'CreatedByID'=> 1,
            'Before'=> 1,
         */
        $message = DB::table('place')->insert(
            [ 
             'Name' => $R_name, 
             'CreatedByID' => $R_createdbyid,
             'BeforeID' => $R_before, 
             'created_at'=>  $current]);
        
    

        $place_Id = DB::table('place')->max('Id');

        return $place_Id;
    }




    public function PlaceUpdate(Request $request, $id) 
    {  
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $current = Carbon::now();
              
        /*
        	'Name'=> 'Room X',
            'CreatedByID'=> 1,            'Before'=> 1,
         */

        if($R_name != NULL){
            $message = DB::table('place')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        if($R_before != NULL){
            $message = DB::table('place')->where('id', $id)->update(
                [  'BeforeID' => $R_before ]);  
        }


        $message = DB::table('place')->where('id', $id)->update(
            [
             'updated_at'=>  $current]);   

        return $id;
    }


}
