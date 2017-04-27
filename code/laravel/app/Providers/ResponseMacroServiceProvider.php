<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('error', function($message, $status = 400) {
            return Response::json([
                'errors' => true,
                'message' => $message
            ], $status);
        });

        Response::macro('success', function($data) {
            return Response::json([
                'errors' => false,
                'data' => $data
            ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
