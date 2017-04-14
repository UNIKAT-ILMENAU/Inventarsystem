<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use JWTAuth;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['checkToken', 'logout']]);
    }

    public function authenticate(Request $request) {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->input('email'))->first();
        if(!$user || !$user->active) {
            return response()->json(['error' => 'No such user or not activated'], 403);
        }

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

    public function checkToken(Request $request) {
        return 'Valid token';
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
}
