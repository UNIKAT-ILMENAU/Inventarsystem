<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Hash;

use App\Http\Requests;

class AdminController extends Controller
{
	 public function showAllAdmins()
     {
          
        return DB::table('user')
        ->join('member', 'user.member_id', '=', 'member.id')
        ->where('member_id', '!=', 'NULL')
        ->select('user.id as ID', 'user.FirstName as FirstName', 'user.LastName as LastName', 'user.Email as Email', 'member.isActivated as Activated')
        ->get();

    }

    public function showDetailAdmins($id)
     {
        $check = DB::table('user')->where('id', $id)->select('member_id')->pluck('member_id');

        if($check[0] == NULL){
           return 'No Admin';
        }else{ 
            return DB::table('user')
            ->join('member', 'user.member_id', '=', 'member.id')
            ->where('user.id', $id)
            ->select('user.id as ID', 'user.FirstName as FirstName', 'user.LastName as LastName', 'user.Email as Email', 'user.Street as Street', 'user.City as City', 'user.ZIP as ZIP', 'user.MobilePhone as Phone', 'user.Matrikel as Matrikel', 'member.isActivated as Activated', 'member.created_at as AdminCreated', 'member.updated_at as AdminLastUpdated ' )
            ->get();
        }
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
        $R_password = $request->input('password');
        $R_token = $request->input('token');
        $current = Carbon::now(); 

        $checkmail = DB::table('user')->select('Email')->where('Email', $R_email)->pluck('Email');
        $checktoken = DB::table('user')->select('RegistrationToken')->where('Email', $R_email)->where('RegistrationToken', $R_token)->pluck('RegistrationToken');



        if($checkmail == NULL)
        {
            return 'Email not found';
            
        }
        else
        {
	        if($checktoken == NULL)
            {
                return 'Email found. But Token invalid';
           
            }
            else
            {
                 $message = DB::table('member')->insert(
                ['password' => Hash::make($R_password), 
                'isActivated' => 1,
                'isAdmin' => 1,
                ]);

            $member_Id = DB::table('member')->max('Id');

            $message = DB::table('user')->where('Email', $R_email)->update(
                [
                'member_id' => $member_Id,
                'FirstName' => $R_firstname, 
                'LastName' => $R_lastname,
                'Street' => $R_street,
                'City' => $R_city,
                'ZIP' => $R_zip,
                'MobilePhone' => $R_mobile,
                'Matrikel' => $R_matrikel,            
                'updated_at' => $current]);

            $message = DB::table('user')->where('RegistrationToken', $R_token)->update(['RegistrationToken' => NULL]);


            return 'User updated to Admin';

            }
    	}
    	return 'WHOOPS';
    }

    public function AdminDeactivate($id) 
    {  
        DB::table('user')
            ->join('member', 'user.member_id', '=', 'member.id')
            ->update(['member.isActivated' => 0]);


        return 'Success';
    }


    public function setPassword(Request $request, $id) 
    {  
        $R_oldpassword = $request->input('oldpassword');
        $R_newpassword = $request->input('newpassword');

        $checkpw = DB::table('user')->join('member', 'user.member_id', '=', 'member.id')->where('user.id', $id)->select('member.password')->get();

        $R_oldpassword

        if('')
        {

        }


        return 'Success';
    }
}
