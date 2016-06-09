<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Requests;

class RentalController extends Controller
{

    public function showAllRentals()
     {
          
        $borrower = DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->select('rentalrelation.RentalID as ID', 'user.Lastname as Lastname', 'user.Firstname as Firstname', 'user.Email as Email', 'rental.EndDate as EndDate')
        ->groupBy('rentalrelation.RentalID')
        ->get();

   

        $item_id = DB::table('rentalrelation')->select('rentalrelation.ItemID')->get();

        foreach ($item_id as $item_id)
        {

            $item = DB::table('rentalrelation')
            ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
            ->select('item.id as ItemID', 'item.Name as Itemname', 'item.State as State')
            ->get();
            
        }


        return [$borrower, $item];
    }

    public function showSingleRentals($id)
     {
          
        $borrower = DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rentalrelation.RentalID', $id)
        ->select('rental.User_id as UserID','user.LastName as Lastname', 'user.FirstName as FirstName','rental.EndDate as EndDate', 'rental.EndDate as EndDate','rental.CreatedByID as CreatedByID')
        ->groupBy('rental.User_id')
        ->get();

        

        $item_id = DB::table('rentalrelation')->where('rentalrelation.RentalID', $id)->select('rentalrelation.ItemID')->get();

        

        foreach ($item_id as $item_id)
        {

            $item = DB::table('rentalrelation')
            ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
            ->where('rentalrelation.RentalID', $id)
            ->select('item.id as ItemID', 'item.Name as Itemname', 'item.State as State', 'rentalrelation.BroughtBack as BroughtBack')
            ->get();;
            
        }


        return [$borrower, $item];
    }


    public function showOpenRentals()
     {
          
        return DB::table('rentalrelation')
        ->join('rental', 'rental.id', '=', 'rentalrelation.RentalID')
        ->join('item', 'item.id', '=', 'rentalrelation.ItemID')
        ->join('user', 'user.id', '=', 'rental.User_id')
        ->where('rentalrelation.state', '!=', '1')
        ->select('rental.id as ID', 'rental.User_id as UserID','user.LastName as Lastname', 'user.FirstName as FirstName')
        ->groupBy('rental.ID')
        ->get();

    }

    public function store(Request $request)
    {
        $R_user_id = $request->input('userid');
        $R_CreatedByID = $request->input('createdbyid');
        $R_EndDate = $request->input('enddate');
        $current = Carbon::now();
        $ids = $request ->input('ids');

        $message = DB::table('rental')->insert(
            ['User_id' => $R_user_id,
             'CreatedByID' => $R_CreatedByID,
             'EndDate' => $R_EndDate,
             'created_at' => $current]);

        $rental_id = DB::table('rental')->max('id');
        

        foreach (json_decode($ids) as $ids1)
        {

            $ItemState = DB::table('item')->select('State')->where('Id', '=', $ids1)->pluck('State');

            if($ItemState[0] == 1)
            {
              
            }else{
                return "Item (ID): ". $ids1 . " is not available";
            }
        }


        foreach (json_decode($ids) as $ids)
        {

            $ItemState = DB::table('item')->select('State')->where('Id', '=', $ids)->pluck('State');

                DB::table('item')->where('id', $ids)->update(
                [
                 'State' => 0
                ]);
                DB::table('rentalrelation')->insert(
                ['RentalID' => $rental_id,
                 'ItemID' => $ids,
                 'State' => 0
                ]);

                DB::table('rental')->where('id', $rental_id)->update(
                [
                 'State' => 0
                ]);
    
        }

        return "The rental was successful.";
    }

    public function BringBackSingle(Request $request, $id) 
    {  
        $itemid = $request ->input('itemid');
        $current = Carbon::now();

        $message = DB::table('rentalrelation')
            ->where('ItemID', $itemid)
            ->where('State', 0)
            ->update(['State' => 1, 'BroughtBack' => $current]);   
        
        DB::table('item')
            ->where('id', $itemid)
            ->where('State', 0)
            ->update(['State' => 1, 'updated_at' => $current]);      

        $itemid = DB::table('rentalrelation')->where('RentalID', $id)->select('ItemID')->pluck('ItemID');
        $check = true;

        foreach ($itemid as $itemids)
        {
            $state = DB::table('rentalrelation')->where('RentalID', $id)->where('ItemID', $itemids)->select('State')->pluck('State');

           

            if($state == [0])
            {
                $check = false;
                return 'Success';
            }
            
        }
        

        if($check = true)
        {
            DB::table('rental')
            ->join('rentalrelation', 'rental.id', '=', 'rentalrelation.RentalID')
            ->where('rental.id', $id )->update(
            [
             'rental.State' => 1,

             'rental.updated_at' => $current
            ]);
        }



        return 'Rental complete';
    }

    public function BringBackAll(Request $request, $id) 
    {  
        $ids = $request ->input('ids');
        $current = Carbon::now();

        foreach (json_decode($ids) as $ids)
        {
        	DB::table('rentalrelation')->where('RentalID', $id)->update(
            [
             'rentalrelation.State' => 1,
            ]); 

            DB::table('item')->where('id', $ids)->update(
            [
             'item.State' => 1,
             'item.updated_at' => $current
            ]);

            DB::table('rental')
            ->join('rentalrelation', 'rental.id', '=', 'rentalrelation.RentalID')
            ->where('rental.id', $id )->update(
            [
             'rental.State' => 1,

             'rental.updated_at' => $current
            ]);
            

        
        }
		return 'Success';
    }




    public function RentalDelete(Request $request, $id) 
    {  
        
        $message = DB::table('rental')->where('id', $id)->delete();

        return 'Success';
    }
  
}
