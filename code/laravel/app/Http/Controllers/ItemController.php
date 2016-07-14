<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\CategoryController;


use App\Http\Requests;

class ItemController extends Controller
{
    ///////////////////////////////////////////////////////////////////
    //////////////// PUBLIC METHODES

    //=====================================================
    // This methode returns all item id's with visible == 1
    // Used: /api/v1/item/allIds
    //=====================================================
    public function showAllIds()
    {
        //return all item ids without keys (only the value)
        return DB::table('item')->select('id')->where('Visible', 1)->orderBy('id')->pluck('id');

    }

    //===================================================
    // This methode returns all items with visible == 1
    // Used: /api/v1/item/allItems
    //===================================================
    public function showAllItems()
    {
        /*
        ! DANGEROUS ! ! DANGEROUS ! ! DANGEROUS ! ! DANGEROUS !
        TO MANY DATAS CAN KILL THE SQL SERVER OR CAUSES A LONG DELAY IN REQUEST
        */

        return DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->where('Visible', 1)
            ->select('item.Id', 'item.Name', 'item.State', 'category.name as Category','material.BuildType', 'material.SalePrice', 'material.StorageValue', 'item.material_id')
            ->get();

    }

    //============================================================
    // This methode returns item information for a specific item
    // Used: /api/v1/item/{id}
    //============================================================
    public function SingleItem($id) 
    {
       
        return DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->where('item.id', '=', $id)
            ->select('item.Id', 'item.Name', 'item.State', 'category.name as Category','material.BuildType', 'material.SalePrice', 'material.StorageValue')
            ->get();
    }

    //==================================================================
    // This methode returns item detailinformation for a specific item
    // Used: /api/v1/item/details/{id}
    //==================================================================
    public function SingleDetailItem($id)
    {
        return DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->where('item.id', '=', $id)
            ->select('item.Id', 'item.Name', 'item.State', 'category.name as Category','material.BuildType', 'item.Description', 'material.SalePrice','UoM', 'UoM_short', 'material.StorageValue', 'item.material_id')
            ->get();

    }
    

    ///////////////////////////////////////////////////////////////////
    //////////////// RESTRICTED METHODES

    //================================================================
    // This methode returns several db information for the dashboard
    // Used: /api/v1/restricted/dashboard/Items
    //================================================================
    public function ItemCount() 
    {  
        //counts all items
        $items = DB::table('item')->count();
        //counts all items that are not available
        $notavailableitems = DB::table('item')->where('State', 0)->count();
        //counts all devices
        $devices = DB::table('item')->where('material_id', 1)->count();
        //counts all materials
        $materials = DB::table('item')->where('material_id','!=', 1)->count();
        //counts all devices that are currently missing
        $missingdevices = DB::table('item')->where('material_id', 1)
                                           ->where('State', 3) ->count();
        //counts all items that are visible in the public list
        $visibleitems = DB::table('item')->where('Visible', 1) ->count(); 
        //counts all items that are invisible in the public list
        $invisibleitems = DB::table('item')->where('Visible', 0) ->count(); 
        //counts all currently open rentals
        $openrentals = DB::table('rental')->where('State', 0)->count();
        //counts all closed rentals
        $closedrentals = DB::table('rental')->where('State', 1)->count();
        //counts all rented items
        $renteditems =  DB::table('rentalrelation')->where('State','=', 0)->count();
        //counts all rented devices
        $renteddevices =  DB::table('rentalrelation')->where('State','=', 0)
                                                     ->where('Amount', '=', NULL)->count();
        //counts all admins
        $admins = DB::table('member')->where('isAdmin', 1)->count();
        //counts all places
        $places = DB::table('place')->count();
        //counts all category
        $category = DB::table('category')->count();
        
        return [$items, $notavailableitems, $devices, $materials, $missingdevices, $visibleitems, 
                $invisibleitems, $openrentals, $closedrentals, $renteditems, $renteddevices, $admins,
                $places, $category];
    }

    //===================================================
    // This methode returns all item id's 
    // Used: /api/v1/restricted/item/allIds
    //===================================================
    public function RestrictedshowAllIds()
    {
        //return all item ids without keys (only the value)
        return DB::table('item')->select('id')->orderBy('id')->pluck('id');

    }

    //===================================================
    // This methode returns all items
    // Used: /api/v1/restricted/item/allItems
    //===================================================
    public function RestrictedshowAllItems()
    {
        /*
        ! DANGEROUS ! ! DANGEROUS ! ! DANGEROUS ! ! DANGEROUS !
        TO MANY DATAS CAN KILL THE SQL SERVER OR LONG DELAY
        */
        
        $item = DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->join('place', 'item.PlaceStartID', '=', 'place.id')
            ->select('item.Id', 'item.Name', 'item.State', 'category.name as Category','material.BuildType', 'material.StorageValue', 'material.CriticalStorageValue', 'item.material_id', 'item.Visible as PublicVisible','item.Deleted as Deactivated',  'item.created_at as Created_at', 'item.updated_at as Updated_at', 'material.SalePrice as SalePrice')
            ->orderBy('id')
            ->get(); 

        //calling the PlaceRoute function from PlaceController
        // to use this function you need to -- use App\Http\Controllers\PlaceController; --
        $place_route = (new PlaceController)->PlaceRoute();            
        
        //insert $place_route in array
        for($i = 0; $i < sizeof($item); $i++){

            $item[$i] ->Place  = $place_route[$i];

        }

        //calling the PlaceRoute function from PlaceController
        // to use this function you need to -- use App\Http\Controllers\CategoryController; --
        $cat_route = (new CategoryController)->CategoryRoute();
 
        //insert $cat_route in array
        for($i = 0; $i < sizeof($item); $i++){

            $item[$i] ->Category = $cat_route[$i];

        }

        return $item; 
    }

    //============================================================
    // This methode returns item information for a specific item
    // Used: /api/v1/restricted/item/{id}
    //============================================================
    public function RestrictedSingleItem($id)
    {
   
        return DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->join('place', 'item.PlaceStartID', '=', 'place.id')
            ->where('item.id', '=', $id)
            ->select('item.Id', 'item.Name', 'item.State', 'category.name as Category','material.BuildType', 'material.StorageValue',
             'material.CriticalStorageValue', 'place.name as Place', 'item.Visible as PublicVisible','item.Deleted as Deactivated', 'item.created_at as Created_at', 'item.updated_at as Updated_at','material.SalePrice as SalePrice')
            ->get();

    }           

    //==================================================================
    // This methode returns item detailinformation for a specific item
    // Used: /api/v1/restricted/item/details/{id}
    //==================================================================
    public function RestrictedSingleDetailItem($id)
    {
        return DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->join('category', 'item.CategoryStartID', '=', 'category.id')
            ->join('place', 'item.PlaceStartID', '=', 'place.id')
            ->where('item.Id', '=', $id)
            ->select('item.Id', 'item.Name', 'item.State', 'place.id as Place', 'place.name as PlaceName', 'category.name as Category', 'category.id as CategoryID','material.BuildType','item.Description','material.UoM', 'material.UoM_short', 'item.Visible as PublicVisible','item.Deleted as Deactivated', 'material.StorageValue', 'material.CriticalStorageValue','item.created_at as Created_at', 'item.updated_at as Updated_at', 'material.SalePrice as SalePrice', 'item.material_id')
            ->get();
    }

    //===========================================================
    // This methode returns the item history for a specific item
    // Used: /api/v1/restricted/item/getHistory/{id}
    //===========================================================
    public function History($id)
    {
        return DB::table('history')
            ->join('comment', 'history.CommentID', '=', 'comment.id')
            ->join('event', 'history.Event_ID', '=', 'event.id')
            ->join('user', 'user.id', '=', 'history.CreatedByID')
            ->where('Item_ID',$id)
            ->select('comment.Comment as Comment', 'history.CreatedByID as CreatedByID', 'user.LastName as Lastname', 'user.FirstName as Firstname', 'history.Event_ID as Event_ID', 'event.Name as EventName', 'history.created_at as created_at')
            ->orderBy('created_at', 'desc')
            ->get();


    }

    //////////////////////////////////////////////////////////////////////
    //////////////// CREATE DEVICE/MATERIAL

    //=====================================================
    // This methode creates a new device and history entry
    // Used: /api/v1/restricted/device/create
    //=====================================================
    public function DeviceStore(Request $request)
    {   
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_state = $request->input('state');
        $R_createdbyid = $request->input('createdbyid'); 
        $R_place = $request->input('place');
        $R_category = $request->input('category');
        $R_description = $request->input('description');
        $R_attachment = $request->input('attachment');
        $R_comment = $request->input('comment');    
        $R_visible = $request->input('visible');
        $current = Carbon::now('Europe/Berlin');
               
        /*
            'Name'=> 'Phillips screwdriver',
            'State'=> 1,
            'Description'=> 'description screwdriver',
            'deleted'=> 0,
            'visible'=> 1,
            'CreatedByID'=> 1,
            'PlaceStartID'=> 5,
            'CategoryStartID'=> 2,
            'material_id'=> 1,
            'Attachment_ID'=> 1,
            'Comment'=> 'Created a Srewdriver'
        */

        //Create a new device
        DB::table('item')->insert(
            [ 
             'material_id' => 1, 
             'Name' => $R_name, 
             'State' => $R_state, 
             'CreatedByID' => $R_createdbyid, 
             'PlaceStartID' => $R_place, 
             'CategoryStartID' => $R_category, 
             'Description' => $R_description,  
             'Attachment_ID' => $R_attachment,           
             'Deleted' => 0,  
             'Visible' => $R_visible,
             'created_at'=>  $current]);

        //return item_id
        $item_Id = DB::table('item')->max('Id');

        //create comment
        DB::table('comment')->insert(
            [ 
                'Comment'=> $R_comment]); 

        //return Comment_ID
        $Comment_ID = DB::table('comment')->max('Id');

        //create history entry
        DB::table('history')->insert(
            [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $item_Id,
                'created_at'=>  $current,
                'Event_ID'=> 1]); //Event-ID == CreateItem


        return $item_Id;
    }

    //========================================================
    // This methode creates a new material and history entry
    // Used: /api/v1/restricted/material/create
    //========================================================
    public function MaterialStore(Request $request)
    {   
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_state = $request->input('state');
        $R_createdbyid = $request->input('createdbyid');
        $R_place = $request->input('place');
        $R_buildtype = $request->input('buildtype');
        $R_category = $request->input('category');
        $R_description = $request->input('description');
        $R_visible = $request->input('visible');
        $R_saleprice = $request->input('saleprice');
        $R_uom = $request->input('uom');
        $R_uom_short = $request->input('uom_short');
        $R_storagevalue = $request->input('storagevalue');
        $R_criticalstoragevalue = $request->input('criticalstoragevalue');
        $R_comment = $request->input('comment');
        $current = Carbon::now('Europe/Berlin');
        
        /*
            'Name'=> 'screws',
            'State'=> 1,
            'Description'=> 'description screwdriver',
            'BuildType'=> 'cross',
            'deleted'=> 0,
            'visible'=> 1,
            'CreatedByID'=> 1,
            'PlaceStartID'=> 5,
            'CategoryStartID'=> 2,
            'material_id'=> 1,
            'Attachment_ID'=> 1,
            'Saleprice'=> 3.50.
            'UoM'=> 'Kilogram'
            'UoM_short'=> 'kg',
            'StorgaeValue'=> 300,
            'CriticalStorageValue'=> 200,
            'Comment'=> 'Created a srew'
        */

        //creates a new material     
        DB::table('material')->insert(
            [ 
             'StorageValue' => $R_storagevalue, 
             'CriticalStorageValue' => $R_criticalstoragevalue, 
             'UoM' => $R_uom, 
             'UoM_short' => $R_uom_short,
             'BuildType' => $R_buildtype, 
             'SalePrice' => $R_saleprice, 
             ]);

        //return material_id
        $R_material_id = DB::table('material')->max('Id');

        //creates a new item with refference to material (material_id)
        $message = DB::table('item')->insert(
            [ 
             'material_id' => $R_material_id, 
             'Name' => $R_name, 
             'State' => $R_state, 
             'CreatedByID' => $R_createdbyid, 
             'PlaceStartID' => $R_place,  
             'CategoryStartID' => $R_category, 
             'Description' => $R_description,              
             'Deleted' => 0,  
             'Visible' => $R_visible,
             'created_at'=>  $current ]);
        
        //return item_id
        $item_Id = DB::table('item')->max('Id');

        //create comment
        DB::table('comment')->insert(
            [ 
                'Comment'=> $R_comment]); 

        //return Comment_ID
        $Comment_ID = DB::table('comment')->max('Id');

        //create history entry
        DB::table('history')->insert(
            [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $item_Id,
                'created_at'=>  $current,
                'Event_ID'=> 1]); //Event-ID == CreateItem


        return $item_Id;
    }

    /////////////////////////////////////////////////////////////////////////
    //////////// UPDATE DEVICE/MATERIAL

    //============================================================
    // This methode updates a device and creates a history entry
    // Used: /api/v1/restricted/device/update/{id}
    //============================================================
    public function DeviceUpdate(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_state = $request->input('state');
        $R_place = $request->input('place');
        $R_category = $request->input('category');
        $R_description = $request->input('description');
        $R_comment = $request->input('comment');
        $R_visible = $request->input('visible');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now('Europe/Berlin');
              
        /*
            'Name'=> 'Phillips screwdriver',
            'State'=> 1,
            'Description'=> 'description screwdriver',
            'visible'=> 1,
            'PlaceStartID'=> 5,
            'CategoryStartID'=> 2,
            'Comment'=> 'Updated a Srewdriver'

        */

        //if variable not NULL -> update value in table
        if($R_name != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        //if variable not NULL -> update value in table
        $message = DB::table('item')->where('id', $id)->update(
                [  'State' => $R_state ]);  

        //if variable not NULL -> update value in table
        if($R_category != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'CategoryStartID' => $R_category ]);  
        }
        //if variable not NULL -> update value in table
        if($R_place != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'PlaceStartID' => $R_place ]);  
        }
        //if variable not NULL -> update value in table
        if($R_description != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'Description' => $R_description ]);  
        }
        //if variable not NULL -> update value in table
        $message = DB::table('item')->where('id', $id)->update(
                [  'visible' => $R_visible ]); 

        //update "updated_at" with current time
        DB::table('item')->where('id', $id)->update(
            [
             'updated_at'=>  $current]);   

        //create comment
        DB::table('comment')->insert(
            ['Comment'=> $R_comment]); 

        //return Comment_ID
        $Comment_ID = DB::table('comment')->max('Id');

        //create history entry
        DB::table('history')->insert(
            [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $id,
                'created_at'=>  $current,
                'Event_ID'=> 3]); //Event-ID == UpdateItem


        return 'Success';
    }


    //==============================================================
    // This methode updates a material and creates a history entry
    // Used: /api/v1/restricted/material/update/{id}
    //==============================================================
    public function MaterialUpdate(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_state = $request->input('state');
        $R_place = $request->input('place');
        $R_buildtype = $request->input('buildtype');
        $R_category = $request->input('category');
        $R_description = $request->input('description');
        $R_saleprice = $request->input('saleprice');
        $R_uom = $request->input('uom');
        $R_uom_short = $request->input('uom_short');
        $R_criticalstoragevalue = $request->input('criticalstoragevalue');
        $R_comment = $request->input('comment');
        $R_visible = $request->input('visible');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now('Europe/Berlin');
              
        /*
            'Name'=> 'screws',
            'State'=> 1,
            'Description'=> 'description screwdriver',
            'BuildType'=> 'cross',
            'visible'=> 1,
            'PlaceStartID'=> 5,
            'CategoryStartID'=> 2,
            'material_id'=> 1,
            'Attachment_ID'=> 1,
            'Saleprice'=> 3.50.
            'UoM'=> 'Kilogram'
            'UoM_short'=> 'kg',
            'CriticalStorageValue'=> 200,
            'Comment'=> 'Updated a srew'
        */

        //if variable not NULL -> update value in table    
        if($R_name != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        //if variable not NULL -> update value in table
        $message = DB::table('item')->where('id', $id)->update(
                [  'State' => $R_state ]);  
        //if variable not NULL -> update value in table
        if($R_place != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'PlaceStartID' => $R_place ]);  
        }
        //if variable not NULL -> update value in table
        if($R_category != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'CategoryStartID' => $R_category ]);  
        }
        //if variable not NULL -> update value in table
        if($R_description != NULL){
            $message = DB::table('item')->where('id', $id)->update(
                [  'Description' => $R_description ]);  
        }
        //if variable not NULL -> update value in table
        $message = DB::table('item')->where('id', $id)->update(
            [  'Visible' => $R_visible ]);

        //if variable not NULL -> update value in table
        if($R_criticalstoragevalue != NULL){
            $message = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $id)->update(
                [  'material.CriticalStorageValue' => $R_criticalstoragevalue ]);
        } 
        //if variable not NULL -> update value in table
        if($R_uom != NULL){
            $message = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $id)->update(
                [  'material.UoM' => $R_uom ]);
        }
        //if variable not NULL -> update value in table
        if($R_uom_short != NULL){
            $message = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $id)->update(
                [  'material.UoM_short' => $R_uom_short ]);
        } 
        //if variable not NULL -> update value in table
        if($R_buildtype != NULL){
            $message = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $id)->update(
                [  'material.BuildType' => $R_buildtype ]);
        } 
        //if variable not NULL -> update value in table
        if($R_uom != NULL){
            $message = DB::table('item')->join('material', 'item.material_id', '=', 'material.id')->where('item.id', $id)->update(
                [  'material.SalePrice' => $R_saleprice ]);
        }

        //update "updated_at" with current time
        $message = DB::table('item')
            ->join('material', 'item.material_id', '=', 'material.id')
            ->where('item.id', $id)->update(
            ['updated_at'=>  $current]);   

        //Comment create
        DB::table('comment')->insert(
            [ 
                'Comment'=> $R_comment]); 

        //return Comment_ID
        $Comment_ID = DB::table('comment')->max('Id');

        //create history entry
        DB::table('history')->insert(
            [ 
                'CommentID'=> $Comment_ID,
                'CreatedByID'=> $R_createdbyid,
                'Item_ID'=> $id,
                'created_at'=>  $current,
                'Event_ID'=> 3]); //Event-ID == UpdateItem


        return $id;
    }


    /////////////////////////////////////////////////////////////////////////
    //////////// DELETE

    //===============================================================
    // This methode deactivates an item and creates a history entry
    // Used: /api/v1/restricted/item/deactivate/{id}
    //===============================================================
    public function ItemDelete(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_comment = $request->input('comment');
        $R_createdbyid = $request->input('createdbyid');
        $current = Carbon::now('Europe/Berlin');

        //update "deleted" -> 1 and "visible" -> 0 (not public visble anymore)
        $message = DB::table('item')->where('id', $id)->update(
            [
             'Deleted' => 1, 
             'Visible' => 0]);   

        //create comment
        DB::table('comment')->insert(['Comment'=> $R_comment]); //

        //return comment_id
        $Comment_ID = DB::table('comment')->max('Id');

        //creates history history
        DB::table('history')->insert(
                    ['CommentID'=> $Comment_ID,
                     'CreatedByID'=> $R_createdbyid,
                     'Item_ID'=> $id,
                     'created_at'=>  $current,
                     'Event_ID'=> 2]);   //Event-ID == DeactivateItem

            return $id;
    }



}



