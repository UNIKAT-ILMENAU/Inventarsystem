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
        /*
        try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
                }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

            }

            // the token is valid and we have found the user via the sub claim
            return response()->json(compact('user'));
            
            $token = JWTAuth::getToken();
            return $token;
            */
            $checkAuth = JWTAuth::attempt();
            if($checkAuth){
                echo "true";
            }else{
                echo "false";
            }
            
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
		//return $token;
        //serialize($token);
        //return response()->json(JWTAuth::setToken($token));
        return response()->json(['token' => JWTAuth::getToken()]);
    }
}
