<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

use App\Http\Requests;

class UserController extends Controller
{
    public function showAllUser()
    { 
        return DB::table('user')
            ->select('user.Id as ID', 'user.Firstname as Firstname', 'user.Lastname as Lastname', 'user.Email as Email', 'user.created_at as CreatedAt', ' user.updated_at as LastUpdated')
            ->get();
    }

    public function showDetailUser($id)
     {
      
        return DB::table('user')
            ->where('user.id', $id)
            ->select('user.id as ID','member_id', 'user.FirstName as FirstName', 'user.LastName as LastName', 'user.Email as Email', 'user.Street as Street', 'user.City as City', 'user.ZIP as ZIP', 'user.MobilePhone as Phone', 'user.Matrikel as Matrikel', 'created_at as UserCreated', 'updated_at as UserLastUpdated ' )
            ->get();

    }


    public function store(Request $request)
    {   
        $R_firstname = $request->input('firstname');
        $R_lastname = $request->input('lastname');
        $R_street = $request->input('street');
        $R_city = $request->input('city');
        $R_zip = $request->input('zip');
        $R_mobile = $request->input('mobile');
        $R_email = $request->input('email');
        $R_matrikel = $request->input('matrikel');
        $current = Carbon::now(); 

        $message = DB::table('user')->insert(
            [
            'FirstName' => $R_firstname, 
            'LastName' => $R_lastname,
            'Street' => $R_street,
            'City' => $R_city,
            'ZIP' => $R_zip,
            'MobilePhone' => $R_mobile,
            'Email' => $R_email,
            'Matrikel' => $R_matrikel,            
            'created_at' => $current]);

        
         $user_Id = DB::table('user')->max('Id');
        return $user_Id;
    }


    public function UserUpdate(Request $request, $id) 
    { 
        $R_name = $request->input('lastname');
        $R_street = $request->input('street');
        $R_city= $request->input('city');
        $R_zip = $request->input('zip');
        $R_mobile = $request->input('mobile');
        $R_email = $request->input('email');
        $R_matrikel = $request->input('matrikel');
        $current = Carbon::now();
             
        $message = DB::table('user')->where('id', $id)->update(
            [
             'LastName' => $R_name, 
             'Street' => $R_street,
             'City' => $R_city,
             'ZIP' => $R_zip, 
             'MobilePhone'=>  $R_mobile,
             'Email'=>  $R_email,
             'Matrikel'=>  $R_matrikel,
             'updated_at'=>  $current,
             ]);   

        return $id;
    }
}
