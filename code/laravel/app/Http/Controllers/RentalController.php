<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Requests;

class RentalController extends Controller
{
	//===============================
    //This methode returns all rentals
    //===============================
    public function showAllRentals()
     {
          
        $borrower = DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->select('rentalrelation.RentalID as Id', 'user.Lastname as Lastname', 'user.Firstname as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate', 'rental.State', 'rental.created_at', 'rental.created_at as Created_at')
        ->groupBy('rentalrelation.RentalID')
        ->get();


        return $borrower;
    }

    //=======================================================
    //This methode returns a specific rental User information
    //=======================================================
    public function showSingleRentals($id)
     {
        //return borrower information   
        $borrower = DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rentalrelation.RentalID', $id)
        ->select('rental.id as Id', 'rental.User_id as UserID','user.LastName as Lastname', 'user.FirstName as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate','rental.CreatedByID as CreatedByID', 'rental.created_at as Created_at', 'rental.state as State')
        ->groupBy('rental.User_id')
        ->get();

        $check = DB::table('rental')
        		->where('rental.id', $id)
        		->select('CreatedByID')
        		->pluck('CreatedByID');

        $creator = DB::table('user')
        		->where('id', $check)
        		->select('user.FirstName as Admin_Firstname', 'user.LastName as Admin_Lastname')
        		->get();

        //return borrower information + item information
        $var1 = [$borrower, $creator];

        return $var1;
    }


    //===================================================
    //This methode returns all item information by rental
    //===================================================
    public function showSingleRentalsItems($id)
     {
        //returns all item ids connected to that rental
        $item_id = DB::table('rentalrelation')->where('rentalrelation.RentalID', $id)->select('rentalrelation.ItemID')->get();

        
        //for each item id return item information
        foreach ($item_id as $item_id)
        {

            $item = DB::table('rentalrelation')
            ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
            ->where('rentalrelation.RentalID', $id)
            ->select('item.id as ItemID', 'item.Name as Itemname', 'item.State as State', 'rentalrelation.State as RState', 'rentalrelation.Amount as Amount', 'rentalrelation.BroughtBack as BroughtBack')
            ->get();
            
        }

        $item_iid = DB::table('rentalrelation')
        ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
        ->where('rentalrelation.RentalID', $id)
        ->select('item.id')
        ->pluck('item.id');

        for($k = 0; $k < sizeof($item_iid); $k++){



                $iid = Db::table('item')
                        ->join('place', 'item.PlaceStartID', '=', 'place.id')
                        ->where('item.id', '=', $item_iid[$k])
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
                
                for($i = sizeof($array)-1; $i > -1; $i-- )
                {
                    $arr[] = implode($array[$i]);                  
                }
                
                $return[] = implode(" - ", $arr); 
                $arr = array();
                $array = array();
                $Before = array();

        }

        for($i = 0; $i < sizeof($item); $i++){
            
            $item[$i] ->Place  = $return[$i];
        }

        return $item;
    }

    //======================================
    //This methode returns all open rentals
    //======================================
    public function showOpenRentals()
     {
          
        return DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rental.state', 0)
        ->select('rentalrelation.RentalID as Id', 'user.Lastname as Lastname', 'user.Firstname as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate', 'rental.State', 'rental.created_at', 'rental.created_at as Created_at')
        ->groupBy('rental.ID')
        ->get();

    }	

    //============================================================
    //This methode creates a new rental and creates history entrys
    //============================================================
    public function store(Request $request)
    {
        $R_email = $request->input('email');
        $R_amounts = $request->input('amounts');
        $R_CreatedByID = $request->input('createdbyid');
        $R_EndDate = $request->input('enddate');
        $R_comment = $request->input('comment');
        $ids = $request ->input('ids');

        $lastname = $request ->input('lastname');
        $firstname = $request ->input('firstname');
        $city = $request ->input('city');
        $street = $request ->input('street');
        $zip = $request ->input('zip');
        $matrikel = $request ->input('matrikel');
        $phone = $request ->input('phone');

        $current = Carbon::now();

        //for each id given -> check if available
        foreach ($ids as $ids1)
        {

            $ItemState = DB::table('item')->select('State')->where('Id', '=', $ids1)->pluck('State');

            if($ItemState[0] == 1)
            {
              
            }else{
                return "Item (ID): ". $ids1 . " is not available";
            }
        }


        //return email if available
        $checkmail = DB::table('user')->select('Email')->where('Email', $R_email)->pluck('Email');

        // Check if user email is already registered
        if($checkmail == NULL)
        {
        	//Create a new user
            DB::table('user')->insert([
                'LastName' => $lastname,
                'FirstName' => $firstname,
                'Street' => $street,
                'City' => $city,
                'ZIP' => $zip,
                'MobilePhone' => $phone,
                'Email' => $R_email,
                'Matrikel' => $matrikel,
                ]);
            
        }
        else
        {
        	//Update user information
            DB::table('user')->where('Email', $R_email)->update([
                'LastName' => $lastname,
                'FirstName' => $firstname,
                ]);
            //if variable not NULL -> update value in table
            if($street != NULL){
            $message = DB::table('user')->where('Email', $R_email)->update(
                [  'Street' => $street ]);  
        	}
        	//if variable not NULL -> update value in table
        	if($city != NULL){
            $message = DB::table('user')->where('Email', $R_email)->update(
                [  'City' => $city ]);  
        	}
        	//if variable not NULL -> update value in table	
        	if($zip != NULL){
            $message = DB::table('user')->where('Email', $R_email)->update(
                [  'ZIP' => $zip ]);  
        	}
        	//if variable not NULL -> update value in table	
        	if($phone != NULL){
            $message = DB::table('user')->where('Email', $R_email)->update(
                [  'MobilePhone' => $phone ]);  
        	}
        	//if variable not NULL -> update value in table	
        	if($matrikel != NULL){
            $message = DB::table('user')->where('Email', $R_email)->update(
                [  'Matrikel' => $matrikel ]);  
        	}		
        }

        //Get the user id for rental
        $userid = DB::table('user')->where('Email', $R_email)->select('id')->pluck('id');

        //Create a new rental
        DB::table('rental')->insert(
            ['User_id' => $userid[0],
             'CreatedByID' => $R_CreatedByID,
             'EndDate' => $R_EndDate,
             'created_at' => $current]);

        //Get the rental id for item history
        $rental_id = DB::table('rental')->max('id');

        //count variable
        $i = 0;
        
        //for each id given 
        foreach ($ids as $ids)
         {
         	$storagecheck = DB::table('item')
         					->where('item.id', $ids)
         					->join('material', 'item.material_id', '=', 'material.id')
         					->select('StorageValue')
         					->pluck('StorageValue');

         	if($storagecheck[0] != NULL){

         		if($storagecheck[0] == $R_amounts[$i]){
         			DB::table('item')->where('id', $ids)->update(
              		[
                 		'State' => 4 //State-ID == not available
                	]);
         		}

         	}else{
	         		//set item "State" in Item to 4
		            DB::table('item')->where('id', $ids)->update(
		                [
		                 'State' => 4 //State-ID == not available
		                ]);

         	}


            //create rentalrealtion entry
            DB::table('rentalrelation')->insert(
                ['RentalID' => $rental_id,
                 'ItemID' => $ids,
                 'Amount' => $R_amounts[$i],
                 'State' => 0
                ]);

            //if $amount not null -> update value in table
            if($R_amounts[$i] != NULL){
                DB::table('item')
                	->join('material', 'item.material_id', '=', 'material.id')
                	->where('item.id', $ids)
                    ->decrement('StorageValue', $R_amounts[$i]);
                }

            //set item "State" in Rental to 0  
            DB::table('rental')->where('id', $rental_id)->update(
                [
                 'State' => 0
                ]);

            //create comment
            DB::table('comment')->insert(['Comment'=> "$R_comment | Rented: $R_amounts[$i]"]); //

            //return comment_id
            $Comment_ID = DB::table('comment')->max('Id');

            //create history entry
            DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_CreatedByID,
                        'Item_ID'=> $ids,
                        'created_at'=>  $current,
                        'Event_ID'=> 4]);

            //increase count variable by 1
            $i ++;
          
         }

        return "The rental was successful. $rental_id";
    }


    /////////////////////////////////////////////////////////
    // vvvvvvvvvvvv has to be commented vvvvvvvvvvvvvvvvvv //
    /////////////////////////////////////////////////////////

    public function BringBackSingle(Request $request, $id) 
    {  
        $itemid = $request ->input('itemid');
        $amount = $request ->input('amount');
        $R_comment = $request->input('comment');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now();


        //updates item state in "rentalrelation"
        DB::table('rentalrelation')
            ->where('ItemID', $itemid)
            ->where('State', 0)
            ->update(['State' => 1, 'BroughtBack' => $current]);   
        
        //updates item state in "item"
        DB::table('item')
            ->where('id', $itemid)
            ->where('State', 4)
            ->update(['State' => 1, 'updated_at' => $current]); 

        //insert Amount_after in rentalrealtion if exist    
        if($amount != NULL){
        	DB::table('rentalrelation')
           			->where('ItemID', $itemid)
           			->update(['Amount_After' => $amount]);

           	//increase storagevalue by $amount for $itemid		
            DB::table('item')
                	->join('material', 'item.material_id', '=', 'material.id')
                	->where('item.id', $itemid)
                    ->increment('StorageValue', $amount); 
        }


        //get every itemid connected to $id in "rentalrealtion"
        $itemids = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');

        //check for every itemids the status
        foreach ($itemids as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');
        
            //if rental incomplete
            if($state == [0])
            {
            	$check = DB::table('rentalrelation')
        			->where('ItemID', $itemid)
        			->select('Amount')  
        			->pluck('Amount');

        		//check if everything brought back	
		        if($amount == $check[0])
		        {
		        	DB::table('comment')->insert(['Comment' => "$R_comment | Brought back: $amount (complete)"]); 

               		$Comment_ID = DB::table('comment')->max('Id');

                	DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 5]);   //Event-ID == UpdateRental

                	return 'success';

                //if something is missing
		        }else
		        {
		        	//BroughtBack HISTORY
		        	DB::table('comment')->insert(['Comment' => "$R_comment | Brought back: $amount (incomplete)"]); //

               		$Comment_ID = DB::table('comment')->max('Id');

                	DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 5]);   //Event-ID == UpdateRental

                	//Lost HISTORY
                	$lost = (int)$check[0] - (int)$amount;
              
                	DB::table('comment')->insert(['Comment' => "Lost: $lost"]); //

               		$Comment_ID = DB::table('comment')->max('Id');

                	DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 9]);   //Event-ID == ItemLost
		        }

                return 'Success';
            }
            
        }
        
        //marks rental as complete
        DB::table('rental')
		        ->join('rentalrelation', 'rental.id', '=', 'rentalrelation.RentalID')
		        ->where('rental.id', $id )->update(
		        [
		         'rental.State' => 1,
		         'rental.updated_at' => $current
		        ]);

        $check = DB::table('rentalrelation')
        			->where('ItemID', $itemid)
        			->select('Amount')  
        			->pluck('Amount');

        if($amount == $check[0])
        {
        	DB::table('comment')->insert(['Comment' => "$R_comment | Brought back: $amount"]); //

       		$Comment_ID = DB::table('comment')->max('Id');

        	DB::table('history')->insert(
                [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $itemid,
                'created_at'=>  $current,
                'Event_ID'=> 5]);   //Event-ID == UpdateRental

        	
        }else
        {
        	//BroughtBack HISTORY
        	DB::table('comment')->insert(['Comment' => "$R_comment | Brought back: $amount"]); //

       		$Comment_ID = DB::table('comment')->max('Id');

        	DB::table('history')->insert(
                [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $itemid,
                'created_at'=>  $current,
                'Event_ID'=> 5]);   //Event-ID == UpdateRental

        	//Lost HISTORY
        	$lost = (int)$check[0] - (int)$amount;
        	DB::table('comment')->insert(['Comment' => "Lost: $lost"]); //

       		$Comment_ID = DB::table('comment')->max('Id');

        	DB::table('history')->insert(
                [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $itemid,
                'created_at'=>  $current,
                'Event_ID'=> 9]);   //Event-ID == ItemLost
        }



        return 'Rental complete';
    }
    
    /*
    public function BringBackMultiple(Request $request, $id) 
    {  
        $ids = $request ->input('ids');
        $R_comment = $request->input('comment');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now();

        foreach (json_decode($ids) as $ids)
        {
        	$message = DB::table('rentalrelation')
            ->where('ItemID', $ids)
            ->where('State', 4)
            ->update(['State' => 1, 'BroughtBack' => $current]);  

            DB::table('item')->where('id', $ids)->update(
            [
             'item.State' => 1,
             'item.updated_at' => $current
            ]);

            DB::table('comment')->insert(['Comment'=> $R_comment]); //

            $Comment_ID = DB::table('comment')->max('Id');


            DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $ids,
                        'created_at'=>  $current,
                        'Event_ID'=> 5]);   //Event-ID == UpdateRental
        
        }

        $itemids = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');

        foreach ($itemids as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');

           
            if($state == [0])
            {
                return 'Success';
            }
            
        }
		return 'RentalComplete';
    }
    */

    public function ItemLost(Request $request, $id) 
    {  
        
        $itemid = $request ->input('itemid');
        $R_comment = $request->input('comment');
        $amount = $request ->input('amount');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now();

        DB::table('rentalrelation')
            ->where('ItemID', $itemid)
            ->where('State', 0)
            ->update(['State' => 3, 'BroughtBack' => $current]);   
        
        DB::table('item')
            ->where('id', $itemid)
            ->where('State', 4)
            ->update(['State' => 3, 'visible' => 0, 'updated_at' => $current]); 

    	if($amount != NULL){
        	DB::table('rentalrelation')
           			->where('ItemID', $itemid)
           			->update(['Amount_After' => 0]);
        }else
        {
        	DB::table('item')
                    ->where('item.id', $itemid)
                    ->update(['State' => 3]);
        }

        $itemids = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');

        foreach ($itemids as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');

           

            if($state == [0])
            {
                DB::table('comment')->insert(['Comment'=> "$R_comment | Lost: $amount"]); //

                $Comment_ID = DB::table('comment')->max('Id');

                DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 9]);   //Event-ID == ItemLost

                return 'Success';
            }
            
        }
        

        DB::table('rental')
	        ->join('rentalrelation', 'rental.id', '=', 'rentalrelation.RentalID')
	        ->where('rental.id', $id )
	        ->update([
				         'rental.State' => 1,
				         'rental.updated_at' => $current
				        ]);

        DB::table('comment')->insert(['Comment'=> "$R_comment | Lost: $amount"]); //

        $Comment_ID = DB::table('comment')->max('Id');

        DB::table('history')->insert(
                    [ 
                    'CommentID'=> $Comment_ID,
                    'CreatedByID'=> $R_createdbyid,
                    'Item_ID'=> $itemid,
                    'created_at'=>  $current,
                    'Event_ID'=> 9]);   //Event-ID == ItemLost

        return 'Rental complete';
    }


}
