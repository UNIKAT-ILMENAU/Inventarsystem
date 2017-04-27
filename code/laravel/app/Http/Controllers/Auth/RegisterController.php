<?php

namespace App\Http\Controllers\Auth;

use App\Mail\InviteMail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     */
    protected function create(Request $request)
    {
        $token = $request->input('token');
        $mail = $request->input('email');
        $user = User::where('email', $mail)->firstOrFail();

        if(Password::broker()->tokenExists($user, $token)) {
            // Update user
            $user->name = $request->input('name');
            $user->password = Hash::make($request->input('password'));
            $user->active = 1;
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

    protected function invite(Request $request) {
        $mail = $request->input('email');

        $user = new User;
        $user->email = $mail;
        $user->save();

        $token = Password::broker()->createToken($user);

        $signup_url = URL::to("/admin/index.html#/createNewAdmin/token=" . $token);

        Mail::to($mail)->send(new InviteMail($signup_url));

    }
}
