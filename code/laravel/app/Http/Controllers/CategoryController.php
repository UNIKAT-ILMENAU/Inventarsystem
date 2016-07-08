<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

use App\Http\Requests;

class CategoryController extends Controller
{

    //=====================================================
    // This methode returns all categorys
    // Used: /api/v1/restricted/category/allCategory
    //=====================================================
    public function showAllCategory()
    { 
        //return all category ids, names, beforeids and descriptions
        return DB::table('category')
            ->select('id', 'Name', 'BeforeID', 'Description')
            ->get();
    }


    //==========================================================
    // This methode returns information for a specific category
    // Used: /api/v1/restricted/category/{id}
    //==========================================================
    public function getCategory($id)
    {
        //return all category ids, names, beforeids and descriptions, creation- and update-date
        return DB::table('category')
            ->where('id',$id)
            ->select('Name', 'Description','BeforeID', 'created_at', 'updated_at')
            ->get();

    }

    //===========================================
    // This methode creates a new category
    // Used: /api/v1/restricted/category/create
    //===========================================
    public function CategoryStore(Request $request)
    {   
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $R_description = $request->input('description');
        $current = Carbon::now();
        
         /*
            'Name'=> 'Tools',
            'BeforeID'=> 1,
            'description' => "This is a description",
            'Before'=> 1,
         */

        //creates a new category
        DB::table('category')->insert(
            [ 
             'Name' => $R_name, 
             'BeforeID' => $R_before, 
             'Description' => $R_description,
             'created_at'=>  $current]);
        
    
        //gets the latest category id
        $category_Id = DB::table('category')->max('Id');

        //returns the category id
        return $category_Id;
    }

    //================================================
    // This methode updates a category
    // Used: /api/v1/restricted/category/update/{id}
    //================================================
    public function CategoryUpdate(Request $request, $id) 
    {  
        //sets variables to incomming values by their keys
        $R_name = $request->input('name');
        $R_before = $request->input('before');
        $R_description = $request->input('description');
        $current = Carbon::now();
              
        /*
            'Name'=> 'Tools',
            'BeforeID'=> 1,
            'description' => "This is a description",
            'Before'=> 1,
         */

        //if variable not NULL -> update value in table 
        if($R_name != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        //if variable not NULL -> update value in table 
        if($R_before != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'BeforeID' => $R_before ]);  
        }
        //if variable not NULL -> update value in table 
        if($R_description != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'Description' => $R_description ]);  
        }

        //sets updated_at to current time
        DB::table('category')->where('id', $id)->update(
            ['updated_at'=>  $current]);   

        //returns the category id
        return $id;

    }

}
