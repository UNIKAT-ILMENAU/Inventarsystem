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
	/*
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        //return $credentials;
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
	*/

    public function authenticate(Request $request)
    {
    	$checkemail = false;
    	$checkpassword = false;



    	//Username Password Request == Datenbank username password
    	$r_email = $request->input('email');
    	$r_password = $request->input('password');

    	$password = Hash::make($r_password);
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

    	$customClaims = ['email' => $r_email, 'password' => $r_password];
		$payload = JWTFactory::make($customClaims);

		$token = JWTAuth::encode($payload);
		return response()->json($token);

        */
    }
}
