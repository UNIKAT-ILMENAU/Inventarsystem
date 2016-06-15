<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;


use App\Http\Requests;

class CategoryController extends Controller
{
    public function showAllCategory()
    { 
        return DB::table('category')
            ->select('id', 'Name')
            ->get();
    }


    public function getCategory($id)
    {
        return DB::table('category')
            ->where('id',$id)
            ->select('Name', 'Description','BeforeID', 'created_at', 'updated_at')
            ->get();

    }





    public function CategoryStore(Request $request)
    {   
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

        $message = DB::table('category')->insert(
            [ 
             'Name' => $R_name, 
             'BeforeID' => $R_before, 
             'Description' => $R_description,
             'created_at'=>  $current]);
        
    

        $category_Id = DB::table('category')->max('Id');

        return $category_Id;
    }




    public function CategoryUpdate(Request $request, $id) 
    {  
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

        if($R_name != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'Name' => $R_name ]);  
        }
        if($R_before != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'BeforeID' => $R_before ]);  
        }
        if($R_description != NULL){
            $message = DB::table('category')->where('id', $id)->update(
                [  'Description' => $R_description ]);  
        }

        $message = DB::table('category')->where('id', $id)->update(
            [
             
             'updated_at'=>  $current]);   

        return $id;
    }

}
