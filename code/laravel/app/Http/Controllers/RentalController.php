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
        ->select('rentalrelation.RentalID as ID', 'user.Lastname as Lastname', 'user.Firstname as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate', 'rental.State', 'rental.created_at')
        ->groupBy('rentalrelation.RentalID')
        ->get();


        return $borrower;
    }

    //======================================
    //This methode returns a specific rental
    //======================================
    public function showSingleRentals($id)
     {
        //return borrower information   
        $borrower = DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rentalrelation.RentalID', $id)
        ->select('rental.User_id as UserID','user.LastName as Lastname', 'user.FirstName as FirstName', 'rental.EndDate as EndDate','rental.CreatedByID as CreatedByID', 'rental.created_at')
        ->groupBy('rental.User_id')
        ->get();

        //returns all item ids connected to that rental
        $item_id = DB::table('rentalrelation')->where('rentalrelation.RentalID', $id)->select('rentalrelation.ItemID')->get();

        //for each item id return item information
        foreach ($item_id as $item_id)
        {

            $item = DB::table('rentalrelation')
            ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
            ->where('rentalrelation.RentalID', $id)
            ->select('item.id as ItemID', 'item.Name as Itemname', 'item.State as State', 'rentalrelation.BroughtBack as BroughtBack')
            ->get();
            
        }

        //return borrower information + item information
        $var1 = [$borrower, $item];

        return $var1;
    }

    //======================================
    //This methode returns all open rentals
    //======================================
    public function showOpenRentals()
     {
          
        return DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rentalrelation.state', '!=', '1')
        ->select('rentalrelation.RentalID as ID', 'user.Lastname as Lastname', 'user.Firstname as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate', 'rental.State', 'rental.created_at')
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
        $message = DB::table('rental')->insert(
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
      		//set item "State" in Item to 0
            DB::table('item')->where('id', $ids)->update(
                [
                 'State' => 0 //State-ID == not available
                ]);

            //create rentalrealtion entry
            DB::table('rentalrelation')->insert(
                ['RentalID' => $rental_id,
                 'ItemID' => $ids,
                 'Amount' => $R_amounts[$i],
                 'State' => 0
                ]);

            //if $amount not null -> update value in table
            if($amount[$i] != NULL){
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

        $message = DB::table('rentalrelation')
            ->where('ItemID', $itemid)
            ->where('State', 0)
            ->update(['State' => 1, 'BroughtBack' => $current]);   
        
        DB::table('item')
            ->where('id', $itemid)
            ->where('State', 0)
            ->update(['State' => 1, 'updated_at' => $current]); 

        if($amount != NULL){
        	DB::table('rentalrelation')
           			->where('ItemID', $itemid)
           			->update(['Amount_After' => $amount]);

             DB::table('item')
                	->join('material', 'item.material_id', '=', 'material.id')
                	->where('item.id', $itemid)
                    ->increment('StorageValue', $amount); 
        }  


        $itemids = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');

        foreach ($itemids as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');

           

            if($state == [0])
            {
                DB::table('comment')->insert(['Comment'=> $R_comment]); //

                $Comment_ID = DB::table('comment')->max('Id');

                DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 5]);   //Event-ID == UpdateRental

                return 'Success';
            }
            
        }
        

            DB::table('rental')
            ->join('rentalrelation', 'rental.id', '=', 'rentalrelation.RentalID')
            ->where('rental.id', $id )->update(
            [
             'rental.State' => 1,

             'rental.updated_at' => $current
            ]);

            DB::table('comment')->insert(['Comment'=> "$R_comment | BroughtBack: $amount[0]"]); //

            $Comment_ID = DB::table('comment')->max('Id');


            DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 5]);   //Event-ID == UpdateRental




        return 'Rental complete';
    }

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
            ->where('State', 0)
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

    public function ItemLost(Request $request, $id) 
    {  
        
        $itemid = $request ->input('itemid');
        $R_comment = $request->input('comment');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now();

        $message = DB::table('rentalrelation')
            ->where('ItemID', $itemid)
            ->where('State', 0)
            ->update(['State' => 3, 'BroughtBack' => $current]);   
        
        DB::table('item')
            ->where('id', $itemid)
            ->where('State', 0)
            ->update(['State' => 3, 'visible' => 0, 'updated_at' => $current]);      

        $itemids = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');

        foreach ($itemids as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');

           

            if($state == [0])
            {
                DB::table('comment')->insert(['Comment'=> $R_comment]); //

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
        ->where('rental.id', $id )->update(
        [
         'rental.State' => 1,

         'rental.updated_at' => $current
        ]);

        DB::table('comment')->insert(['Comment'=> $R_comment]); //

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
