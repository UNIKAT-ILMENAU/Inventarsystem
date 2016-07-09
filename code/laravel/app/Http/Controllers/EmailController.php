<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    //==============================
    // This methode sends an email 
    // Used: /send
    //==============================b
    public function send(Request $request){
        //sets variables to incomming values by their keys
	    $title = $request->input('title');
        $content = $request->input('content');

        Mail::send('emails.welcome', ['title' => $title, 'content' => $content], function ($message)
        {

            $message->from('789d962ab3-169976@inbox.mailtrap.io', 'Christian Nwamba');

            $message->to('789d962ab3-169976@inbox.mailtrap.io');

        });

        return response()->json(['message' => 'Request completed']);      
	}
    //for testing
    //sender should be 789d962ab3-169976@inbox.mailtrap.io
    //receiver should be 789d962ab3-169976@inbox.mailtrap.io
    public function sendInvite($sender, $receiver, $reg_token)
    {
        Mail::send('emails.welcome', ['title' => "Your Invite from UNIKAT", 'content' => "http://inventarsystem.app/admin/index.html#/createNewAdmin/token=" . $reg_token], function ($message)
        {

            $message->from($sender, "UNIKAT");

            $message->to($receiver);

        });
    }
}
