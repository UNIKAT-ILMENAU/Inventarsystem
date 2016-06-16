<?php

namespace App\Http\Middleware;

use Closure;

//JWT Libaries
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;

class JWTCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    /*
    public function handle($request, Closure $next)
    {
        if()

        //show the respond site
        return $next($request);
    }
    */

    public function handle($request, Closure $next)
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
            return $next($request);
        } 
        catch(\Exception $e){
            return response()->json(['error' => 'middleware error.']);
        }
    }
}
