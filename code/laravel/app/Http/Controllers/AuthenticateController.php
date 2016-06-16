<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Hash;
use DB;

//JWT Libaries
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;
//use Tymon\JWTAuth\JWTManager;

class AuthenticateController extends Controller
{
    
    public function checkAuth(Request $request)
    {
        try{
            // try code
            //$token = 'Bearer ' . JWTAuth::getToken();
            $token = JWTAuth::getToken();
            
            $payload = JWTAuth::getPayload($token);
            //return $payload; 

            $manager = JWTAuth::manager();
            $test = $manager->decode($token);

            
            //token is valid --> return the token
            return $token;
        } 
        catch(\Exception $e){
            return response()->json(['error' => 'login failed. no or wrong token was founded.']);
        }

        /*
        //echo "CheckAuth Route";
        //Not so important
        //$token = JWTAuth::parseToken();
        //echo "Parse Header Authorization Token";
        $token = JWTAuth::getToken();
        
        $payload = JWTAuth::getPayload($token);
        //return $payload; 

        $manager = JWTAuth::manager();
        $test = $manager->decode($token);

        
        //$test2 = $manager->getClaims();
        //return response->json_decode($test);
        return $test;
        */
    }
    

    public function createToken(Request $request)
    {
        //try
        //{
        //These vars will be true when the and the hashed password are valid
        $checkemail = false;
        $checkpassword = false;



        //Extract email and username from the request
        $r_email = $request->input('email');
        $r_password = $request->input('password');

        //Testing Hashing the password
        $password = Hash::make($request);
        
        //Try if the user email is in db 
        $db_mail = DB::table('user')
            ->where('Email', $r_email)
            ->select('Email')
            ->pluck('Email');
        
        //when email was founded then change to true
        if($db_mail[0] == $r_email)
        {
            $checkemail = true;
        }

        //search for member id in user table with the email address
        $db_memberid = DB::table('user')
            ->where('Email', $r_email)
            ->select('member_id')
            ->pluck('member_id');

        //search for userid in DB with the email address
        $db_userid = DB::table('user')
            ->where('Email', $r_email)
            ->select('id')
            ->pluck('id');

        //search for the password in member with the userid
        $db_password = DB::table('member')
            ->where('id', '=', $db_memberid[0])
            ->select('password')
            ->pluck('password');


        if(Hash::check($r_password, $db_password[0]))
        {
            $checkpassword = true;
        }


        if($checkpassword&&$checkemail){
            
            //Credentials are right!
            $customClaims = ['User_Id' => $db_userid[0], 'User_Email' => $db_mail[0]];
            $payload = JWTFactory::make($customClaims);
            $token = JWTAuth::encode($payload);
            
            //send token back
            return response()->json(['token' => (string)$token]);

        }elseif (!$checkpassword) {
            return response()->json(['error' => 'The Password is wrong']);
        }elseif (!$checkemail) {
            return response()->json(['error' => 'The Email is wrong']);
        }elseif (!$$checkpassword&&!$checkemail) {
            return response()->json(['error' => 'The Email and the password are wrong']);
        }

        //}
        //catch(\Exception $e){
            //return response()->json(['error' => 'login failed. wrong credentials']);
        //}
        /*
        $customClaims = ['email' => $r_email, 'password' => $r_password];
        $payload = JWTFactory::make($customClaims);

        $token = JWTAuth::encode($payload);
        //return $token;
        //serialize($token);
        //return response()->json(JWTAuth::setToken($token));
        //return response()->json(['token' => JWTAuth::getToken()]);

        return gettype($token);

        */
    }
}
