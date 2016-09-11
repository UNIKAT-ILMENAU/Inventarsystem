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
use Tymon\JWTAuth\Facades\JWTManager;
//use Tymon\JWTAuth\JWTManager;
//invalidate
class AuthenticateController extends Controller
{
    //check if the token is valid
    public function checkAuth()
    {
        try{
            //get the token from the authorization header
            $token = JWTAuth::getToken();

            //get the payload from the token
            $payload = JWTAuth::getPayload($token);

            //get User id from the token
            $p_user_id = AuthenticateController::getUserIdFromToken();

            //check if the user is an admin and activated
            if(AuthenticateController::isUserAdminAndActive($p_user_id)){
                return $token;
            }else{
                return response()->json(['error' => 'you are not an activated user or you dont had administrator rights', 'test' => $p_user_id]);
            }
        }
        catch(\Exception $e){
            return response()->json(['error' => 'login failed. no or wrong token was founded.']);
        }
    }


    //this is for logging in
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
    }

    public function logout(Request $request)
    {
        try{
            //read the token from header
            //$token = JWTAuth::getToken();
            $token = $request->input('token');

            //set the token invalid
            JWTAuth::invalidate($token);

            //return sucess message
            return response()->json(['success' => 'logout was successful']);
        }
        catch(\Exception $e){
            //return error message
            return response()->json(['error' => 'logout failed. no or wrong token was founded.']);
        }
    }


   /////////////////////
   //Helper Functions //
   /////////////////////


    public function getUserIdFromToken()
   {
        //get the token
        $token = JWTAuth::getToken();

        //decode the token
        $payload = JWTAuth::getPayload($token);

        //read the user id from token
        $truth = json_decode($payload);

        //get user id back
        return $truth->User_Id;

   }

   public function getMemberId($userid)
   {
       //check for user in database
       $member_id = DB::table('user')
            ->join('member', 'user.member_id', '=', 'member.id')
            ->select('member.id')
            ->where('user.id', $userid)
            ->first();

       //return memberid
       return $member_id->id;
   }

   public function isUserAdminAndActive($userid)
   {
        $userid = AuthenticateController::getUserIdFromToken();
        //get memberid
        $member_id = AuthenticateController::getMemberId($userid);
       //check for user in database
        $db_request = DB::table('member')
            ->select('member.isAdmin', 'member.isActivated')
            ->where('id', $member_id)
            ->first();
       //check for isAdmin
        $isAdmin = $db_request->isAdmin;
       //check for isActivated
        $isActivated = $db_request->isActivated;

        if($isAdmin===1 && $isActivated===1)
        {
            return true;
        }

        return false;
       //return true or false
       // return false;
   }

   public function CheckUserEmail($userid, $r_email)
   {
        $db_request = DB::table('user')
            ->select('Email')
            ->where('id', $userid)
            ->first();

        $db_mail = $db_request->Email;

        if($db_mail==$r_email)
        {
            return true;
        }

        return false;
   }
   /**
    * getting the UserID from the Email
    * @param  [string] $useremail [the user Email Address]
    * @return [int/boolean]            [if there is an email it will return an int if       not                                then it returns an false]
    */
   public function getUserIdFromEmail($useremail)
   {
        try
        {
        $db_request = DB::table('user')
            ->select('id')
            ->where('Email', $useremail)
            ->first();

        $id = $db_request->id;
        if($id!=NULL){
            return $id;
        }
        }
        catch(\Exception $Exception)
        {
            return -1;
        }

   }
}
