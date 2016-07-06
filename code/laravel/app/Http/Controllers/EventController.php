<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Requests;

class EventController extends Controller
{
    //===================================================
    //This methode returns all events
    //=================================================== 
    public function showAllEvents()
     {
          
        return DB::table('event')
        ->join('user', 'user.id', '=', 'event.CreatedByID')
        ->select('event.id as ID','event.Name as Name', 'event.Description as Description', 'event.EventValue as EventValue','event.CreatedByID as CreatedByID')
        ->orderBy('event.id')
        ->get();
    }


    //===================================================
    //This methode does a event depending on the id given
    //=================================================== 
    public function doEvent(Request $request, $id)
    {
    	switch ($id) {
            //======================================================================
            //Event: Use Material - updates "StorageValue" and creates history entry
            //======================================================================
            case 6: 
                    $itemid = $request->input('itemid');
                    $amount = $request->input('amount');
                    $R_createdbyid = $request->input('createdbyid');
                    $current = Carbon::now();

                    //returns "StorageValue" before 
                    $before = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //reduces "StorageValue" by $amount
                    DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->decrement('StorageValue', $amount);

                    //returns "StorageValue" after    
                    $after = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //array to string
                    $before = implode($before);        
                    $after = implode($after);
                    
                    //creates comment
                    DB::table('comment')->insert(['Comment'=> "Before: $before After: $after Used: $amount"]); //

                    //return comment_id
                    $Comment_ID = DB::table('comment')->max('Id');
                    
                    //creates history entry
                    DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 6]); //Event-ID == UseMaterial

                    break;

            //=========================================================================
            //Event: Refill Material - updates "StorageValue" and creates history entry
            //=========================================================================
            case 7: 
                    $itemid = $request->input('itemid');
                    $amount = $request->input('amount');
                    $R_createdbyid = $request->input('createdbyid');
                    $current = Carbon::now();

                    //returns "StorageValue" before 
                    $before = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //increases "StorageValue" by $amount
                    DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->increment('StorageValue', $amount);

                    //returns "StorageValue" after
                    $after = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //array to string
                    $before = implode($before);        
                    $after = implode($after);

                    //creates comment
                    DB::table('comment')->insert(['Comment'=> "Before: $before After: $after Refilled: $amount"]); //

                    //return comment_id
                    $Comment_ID = DB::table('comment')->max('Id');
    
                    //creates history entry
                    DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 7]); //Event-ID == RefillMaterial

                    
                    break;

            //============================================================================
            //Event: Device defect - sets an device to defectice and creates history entry
            //============================================================================        
            case 8: 
                    $itemid = $request->input('itemid');
                    $comment = $request->input('comment');
                    $R_createdbyid = $request->input('createdbyid');
                    $current = Carbon::now();

                    //updates item state to 2
                    DB::table('item')->where('id', $itemid)->update(['State' => 2]); //State == Device defectice

                    //create comment
                    DB::table('comment')->insert(['Comment'=> $comment]); //

                    //return comment_id
                    $Comment_ID = DB::table('comment')->max('Id');
                    
                    //create history entry
                    DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 8]);   //Event-ID == DeviceDefective

                    break;

            //==================================================================
            //Event: Item lost - marks an item as lost and creates history entry
            //================================================================== 
            case 9: 
                    $itemid = $request->input('itemid');
                    $comment = $request->input('comment');
                    $R_createdbyid = $request->input('createdbyid');
                    $current = Carbon::now();

                    //updates item state to 2
                    DB::table('item')->where('id', $itemid)->update(['State' => 3]); //State == item lost

                    //create comment
                    DB::table('comment')->insert(['Comment'=> $comment]); //

                    //return comment_id
                    $Comment_ID = DB::table('comment')->max('Id');
                    
                    //create history entry
                    DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 9]); //Event-ID == ItemLost

                    break;

            //======================================================================
            //Event: Sell Material -updates "StorageValue" and creates history entry
            //======================================================================         
            case 10:
                    $itemid = $request->input('itemid');
                    $price = $request->input('price');
                    $amount = $request->input('amount');
                    $R_createdbyid = $request->input('createdbyid');
                    $current = Carbon::now();

                    //returns "StorageValue" before 
                    $before = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //reduces "StorageValue" by $amount    
                    DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->decrement('StorageValue', $amount);

                    //returns "StorageValue" after
                    $after = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $itemid)
                        ->select('StorageValue')->pluck('StorageValue');

                    //array to string
                    $before = implode($before);        
                    $after = implode($after);
                    
                    //creates comment 
                    DB::table('comment')->insert(['Comment'=> "Before: $before - After: $after - Sold: $amount - Price: $price €"]); //

                    //return comment_id
                    $Comment_ID = DB::table('comment')->max('Id');
                    
                    //create history entry
                    DB::table('history')->insert(
                        [ 
                        'CommentID'=> $Comment_ID,
                        'CreatedByID'=> $R_createdbyid,
                        'Item_ID'=> $itemid,
                        'created_at'=>  $current,
                        'Event_ID'=> 10]);  //Event-ID == SellMaterial

                    break;
        }

        return 'success';



    	
    }


    
}
