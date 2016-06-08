<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Hash;

//JWT Libaries
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthenticateController extends Controller
{
	
    public function checkAuth(Request $request)
    {
        $token = $request->input('token');
        JWTAuth::setToken($token);
        // this will set the token on the object
        $user = JWTAuth::parseToken();

        // and you can continue to chain methods
        //$user = JWTAuth::parseToken()->authenticate();
        
        echo $user;
    }
	

    public function authenticate(Request $request)
    {
        /*
    	$checkemail = false;
    	$checkpassword = false;



    	//Username Password Request == Datenbank username password
    	$r_email = $request->input('email');
    	$r_password = $request->input('password');

    	$password = Hash::make($request);
    	return $password;
    	/*
    	$db_mail = DB::table('user')
            ->where('Email', $r_email)
            ->select('Email')
            ->get();
            
    	if($db_mail == $r_email)
    	{
    		$checkemail = true;
    	}

    	$db_password = DB::table('user')
            ->where('Email', $r_email)
            ->select('Email')
            ->get();

    	if($db_mail == $r_email)
    	{
    		$checkemail = true;
    	}
        */
        $r_email = $request->input('email');
        $r_password = $request->input('password');

    	$customClaims = ['email' => $r_email, 'password' => $r_password];
		$payload = JWTFactory::make($customClaims);

		$token = JWTAuth::encode($payload);
		return $token;
    }
}
