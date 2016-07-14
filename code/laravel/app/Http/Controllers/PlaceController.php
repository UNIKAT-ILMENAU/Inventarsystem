<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;


use App\Http\Requests;

class PlaceController extends Controller
{
    //=======================================================
    // This methode returns information for all places 
    // Used: /api/v1/restricted/place/allPlace
    //=======================================================
    public function showAllPlace()
    { 
        //returns all place ids, names, beforeids
        return DB::table('place')
            ->select('id', 'Name', 'BeforeID')
            ->get();
    }

    //==============================================================
    // This methode returns place information for a specific place
    // Used: /api/v1/restricted/place/{id}
    //==============================================================
    public function getPlace($id)
    {
        return DB::table('place')
            ->where('id',$id)
            ->select('Name', 'CreatedByID', 'BeforeID','created_at', 'updated_at')
            ->get();

    }

    //======================================
    // This methode creates a new place
    // Used: /api/v1/restricted/place/create
    //======================================
    public function PlaceStore(Request $request)
    {   
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now('Europe/Berlin');
               
        /*
            'Name'=> 'Room X',
            'CreatedByID'=> 1,
            'Before'=> 1,
         */

        //creates new place
        DB::table('place')->insert(
            [ 
             'Name' => $R_name, 
             'CreatedByID' => $R_createdbyid,
             'BeforeID' => $R_before, 
             'created_at'=>  $current]);
        
    
        //gets the latest place id
        $place_Id = DB::table('place')->max('Id');

        //returns place id
        return $place_Id;
    }

    //============================================
    // This methode updates a place
    // Used: /api/v1/restricted/place/update/{id}
    //============================================
    public function PlaceUpdate(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $current = Carbon::now('Europe/Berlin');
              
        /*
        	'Name'=> 'Room X',
            'CreatedByID'=> 1,            'Before'=> 1,
         */

        //if variable not NULL -> update value in table
        if($R_name != NULL){
            $message = DB::table('place')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        //if variable not NULL -> update value in table
        if($R_before != NULL){
            $message = DB::table('place')->where('id', $id)->update(
                [  'BeforeID' => $R_before ]);  
        }

        //sets updated_at to current time
        DB::table('place')->where('id', $id)->update(
            [
             'updated_at'=>  $current]);   

        //returns place id
        return $id;
    }

    //============================================
    // This methode deletes a place
    //============================================
    public function PlaceDelete($id) 
    {  
        //deletes the place
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

    public function PlaceRoute() 
    {  
        
    	//get all item ids in order
        $getid = DB::table('item')
                ->select('id')
                ->orderby('id')
                ->pluck('id');
        
        
        //creating a place path for each item         
        foreach($getid as $getids){
            
                //get the place start id 
                $iid = Db::table('item')
                        ->join('place', 'item.PlaceStartID', '=', 'place.id')
                        ->where('item.id', $getids)
                        ->select('item.PlaceStartID')
                        ->pluck('PlaceStartID');

                //get the place name        
                $array[] = DB::table('place')
                        ->where('id', $iid[0])
                        ->select('Name')
                        ->pluck('Name');

                //get the next place id        
                $Before = DB::table('place')
                        ->where('id', $iid[0])
                        ->select('BeforeID')
                        ->pluck('BeforeID');

                //repeat till no before id is given        
                while($Before[0] != NULL) {

                    //get the place name 
                    $array[] = DB::table('place')
                        ->where('id', $Before[0])
                        ->select('Name')
                        ->pluck('Name');

                    //get the next place id      
                    $Before = DB::table('place')
                        ->where('id', $Before[0])
                        ->select('BeforeID')
                        ->pluck('BeforeID');
                    }; 
                
                //go through the array    
                for($i = sizeof($array)-1; $i >= 0; $i-- )
                {
                    //array to string
                    $arr[] = implode($array[$i]);                  
                }
                
                //array to string, divided by " - "
                $return[] = implode(" - ", $arr); 

                //clear variables
                $arr = array();
                $array = array();
                $Before = array();
               
                
        } 

        return $return;    
        
         

    }
    
}
