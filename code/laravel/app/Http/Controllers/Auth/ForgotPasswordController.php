<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendPasswordMail(Request $request) {

        try {
            $user = User::where('email', $request->input('email'))->firstOrFail();

            $token = Password::broker()->createToken($user);
            $resetUrl = URL::to("admin/index.html#/newPassword/token=" . $token);

            Mail::to($user->email)->send(new PasswordResetMail($resetUrl));
        } catch (Exception $e) {
            return;
        }
    }

    public function resetPassword(Request $request) {
        $token = $request->input('token');
        $mail = $request->input('email');
        $user = User::where('email', $mail)->firstOrFail();

        if(Password::broker()->tokenExists($user, $token)) {
            // Update user
            $user->password = Hash::make($request->input('password'));
            $user->save();

            Password::broker()->deleteToken($user);
        } else {
            // Return invalid token error
            $returnData = array(
                'status' => 'error',
                'message' => 'Invalid token'
            );
            return response($returnData, 401);
        }
    }
}
