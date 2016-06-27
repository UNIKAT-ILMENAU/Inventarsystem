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
            ->select('id', 'Name', 'BeforeID')
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

    public function PlaceDelete($id) 
    {  
        DB::table('place')	
        		->where('id', $id)
        		->delete();

        return 'success';
    }

	
    public function showP()
     {
          
        return DB::table('place')
        	->where('BeforeID', NULL)
            ->select('id', 'Name')
            ->get();

    }

    public function showC($id)
     {
          
        return DB::table('place')
        	->where('BeforeID', $id)
            ->select('id', 'Name')
            ->get();

    }
    /*
    public function test($id) 
    {  
    	


    	$array[] = DB::table('place')
        		->where('id', $id)
        		->select('Name')
        		->pluck('Name');

        $Before = DB::table('place')
        		->where('id', $id)
        		->select('BeforeID')
        		->pluck('BeforeID');


        while($Before[0] != NULL) {

        	$array[] = DB::table('place')
        		->where('id', $Before[0])
        		->select('Name')
        		->pluck('Name');

    		$Before = DB::table('place')
        		->where('id', $Before[0])
        		->select('BeforeID')
        		->pluck('BeforeID');
			}; 
		

		return $array;
    } */ 

    public function PlaceRoute($id) 
    {  
        
    	$iid = Db::table('item')
    			->join('place', 'item.PlaceStartID', '=', 'place.id')
    			->where('item.id', '=', $id)
    			->select('place.id')
    			->pluck('place.id');


    	$array[] = DB::table('place')
        		->where('id', $iid[0])
        		->select('Name')
        		->pluck('Name');

        $Before = DB::table('place')
        		->where('id', $iid[0])
        		->select('BeforeID')
        		->pluck('BeforeID');


        while($Before[0] != NULL) {

        	$array[] = DB::table('place')
        		->where('id', $Before[0])
        		->select('Name')
        		->pluck('Name');

    		$Before = DB::table('place')
        		->where('id', $Before[0])
        		->select('BeforeID')
        		->pluck('BeforeID');
			}; 
	
		$arr = [];

		for($i = sizeof($array)-1; $i > -1; $i-- )
		{
			array_push($arr, implode($array[$i]));	
		}
		return [implode(" - ", $arr)]; 

    }
    
}
