<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Hash;
use Mail;
use JWTAuth;
use Crypt;
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
        $reg_token = $request->input('reg_token'); //NEW NEED TO BE SEND
        // $R_token = $request->input('token');
        $current = Carbon::now(); 
        $checkmail = DB::table('user')->select('Email')->where('Email', $R_email)->first();
        $checktoken = DB::table('user')->select('RegistrationToken')->where('Email', $R_email)->where('RegistrationToken', $reg_token)->pluck('RegistrationToken');

        //$checkmail = $checkmail->Email;
        //return $checkmail;

        if($checkmail == NULL)
        {
            return response()->json(['error' => 'Email not found']);
            //return 'Email not found';
            
        }
        else
        {
            if($checktoken == NULL)
            {
                return response()->json(['error' => 'Email found. But Token invalid']);
                //return 'Email found. But Token invalid';
           
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

            $message = DB::table('user')->where('RegistrationToken', $reg_token)->update(['RegistrationToken' => NULL]);


            return 'User updated to Admin';

            }
        }
        return 'WHOOPS';
    }

    public function AdminDeactivate($id) 
    {  
        DB::table('user')
            ->join('member', 'user.member_id', '=', 'member.id')
            ->where('user.id', $id)
            ->update(['member.isActivated' => 0]);


        return 'Success';
    }


    public function setPassword(Request $request, $id) 
    {  
        $R_oldpassword = $request->input('oldpassword');
        $R_newpassword = $request->input('newpassword');

        $checkpw = DB::table('user')->join('member', 'user.member_id', '=', 'member.id')->where('user.id', $id)->select('member.password')->get();
    }

    public function invite(Request $request)
    {
   
        //get the email from the response and set a check varaible
        $r_email = $request->input('email');
        $_mail_is_in_db = false;

        //make an db request if mail is in database
        $db_email = DB::table('user')->where('user.Email', $r_email)->select('Email')->get();
        
        //set check if mail is allready in database
        if($db_email!=NULL){
            $_mail_is_in_db = true;
        }
        
        //if mail is allready in db than update or set a new hash code and send it
        if($_mail_is_in_db){
            $user_id = DB::table('user')->where('Email', $r_email)->select('id')->get();

            //generate the hash token for registration
            $reg_hash_token = Hash::make($r_email);

            //insert the token in the database
            DB::table('user')->where('Email', '=', $r_email)
            ->update(array('RegistrationToken' => $reg_hash_token));

            //testing some stuff
            $sender = '789d962ab3-169976@inbox.mailtrap.io'; //this should be the unikat email
            $receiver = '789d962ab3-169976@inbox.mailtrap.io'; //here will be the r_email
            //Here the email with the invite link is created
            AdminController::sendEmail($sender, $receiver, 'Invite', $reg_hash_token);

        return response()->json(['message' => 'Request completed']);    
            //return "hash is saved in db";
        }else if($_mail_is_in_db == false){
            //insert new user with email, and create a hash token
            //
            //generate the hash token for registration
            $reg_hash_token = Hash::make($r_email);

            //insert email and token in the database
            DB::table('user')
            ->insert(array('Email' => $r_email, 'RegistrationToken' => $reg_hash_token));

            return response()->json(['message' => 'Request completed']);
        }
    }

    public function sendForgottenMailToken(Request $request)
    {
   
        //get the email from the response and set a check varaible
        $r_email = $request->input('email');
        $_mail_is_in_db = false;

        //make an db request if mail is in database
        $db_email = DB::table('user')->where('user.Email', $r_email)->select('Email')->get();
        
        //set check if mail is allready in database
        if($db_email!=NULL){
            $_mail_is_in_db = true;
        }
        
        //if mail is allready in db than update or set a new hash code and send it
        if($_mail_is_in_db){
            $user_id = DB::table('user')->where('Email', $r_email)->select('id')->get();

            //generate the hash token for registration
            $reg_hash_token = Hash::make($r_email);

            //insert the token in the database
            DB::table('user')->where('Email', '=', $r_email)
            ->update(array('RegistrationToken' => $reg_hash_token));

            //testing some stuff
            $sender = '789d962ab3-169976@inbox.mailtrap.io'; //this should be the unikat email
            $receiver = '789d962ab3-169976@inbox.mailtrap.io'; //here will be the r_email
            //Here the email with the invite link is created
            AdminController::sendEmail($sender, $receiver, 'New Password', $reg_hash_token);

        return response()->json(['message' => 'Request completed']);    
            //return "hash is saved in db";
        }else if($_mail_is_in_db == false){
            //insert new user with email, and create a hash token
            //
            //generate the hash token for registration
            $reg_hash_token = Hash::make($r_email);

            //insert email and token in the database
            DB::table('user')
            ->insert(array('Email' => $r_email, 'RegistrationToken' => $reg_hash_token));

            return response()->json(['message' => 'Request completed']);
        }
    }


    public function changePasswordFromCurrentUser(Request $request)
    {
        try
        {

        $r_password = $request->input('password');
        $r_oldpassword = $request->input('oldpassword');
        $r_password = Hash::make($r_password);
        //get user and member id
        //$user_id    = AuthenticateController::getUserIdFromToken();
        $member_id  = AdminController::getMemberId(AdminController::getUserIdFromToken());

        //$member_id = 5;
        //save new password to member password
        
        $oldpwcheck = false;

        $old_db = DB::table('member')->where('id', '=', $member_id)->select('password')->first();
        $oldpwcheck = Hash::check($r_oldpassword, $old_db->password);

        //return $old_db->password;

        if($oldpwcheck)
        {
            DB::table('member')->where('id', '=', $member_id)
            ->update(array('password' => $r_password));            
        }

        return response()->json(['message' => 'Password was changed']);
        }
        catch(\Exeption $ex)
        {
        return response()->json(['message' => 'Password was changed']);
        }
    }

    public function changeForgottenPassword(Request $request)
    {
        try
        {

        $r_email = $request->input('email');
        $r_password = $request->input('password');
        $r_token = $request->input('token');
        $r_password = Hash::make($r_password);
        $member_id = -1;
        
        if(AdminController::getUserIdFromEmail($r_email)!=-1)
        {
            $member_id  = AdminController::getMemberId(AdminController::getUserIdFromEmail($r_email));
        }

        $hasRegToken = false;

        $test_token = DB::table('user')->where('member_id', $member_id)
        ->select('RegistrationToken')->first();

        //return $test_token->RegistrationToken;
        //return $test_token; 


        if ($test_token->RegistrationToken===$r_token) {
            $hasRegToken = true;
            //return "true";
        }
        if($member_id!=-1 && $hasRegToken)
        {
            //save new password to member password
            //
            DB::table('member')
            ->where('member.id', '=', $member_id)
            ->update(['member.password' => $r_password]);

            DB::table('user')
            ->where('member_id', '=', $member_id)
            ->update(['RegistrationToken' => NULL]);

            return response()->json(['message' => 'Password was changed']);
        }
        return response()->json(['message' => 'Password was not changed']);

        }
        catch(\Exeption $ex)
        {
        return response()->json(['message' => 'Password was changed']);
        }
    }

    //send the mail to the user email address
    //use the invite api!
    public function resetPasswordFromEmail(Request $request)
    {
        return "please use the invite controller";
    }

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

    public function sendEmail($sender_text, $receiver_text, $title_text, $reg_token)
    {
       //echo $data[0];
        
        $title = $title_text;
        $sender = $sender_text;
        $receiver = $receiver_text;

        //echo "sender: " . $sender . " receiver: " . $receiver;
        //return true;
        //$content = $request->input('content');

        Mail::send('emails.welcome', ['title' => $title, 'content' => 'http://inventarsystem.app/admin/index.html#/createNewAdmin/token=' . $reg_token], function ($message) use ($sender, $receiver)
        {

            $message->from($sender, 'inventarsystem');

            $message->to($receiver);

        });

        return response()->json(['message' => 'Request completed']);

        /*Mail::send('emails.welcome', ['title' => "Your Invite from UNIKAT", 'content' => "http://inventarsystem.app/admin/index.html#/createNewAdmin/token=" . $reg_token, 'sender' => $sender, 'receiver' => $receiver], function ($message)
        {

            $message->from($sender, "UNIKAT");

            $message->to($receiver);

        });
        */
    }
}
