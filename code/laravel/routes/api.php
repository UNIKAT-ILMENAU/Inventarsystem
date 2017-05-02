<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return "Api v1";
    });

    Route::post('/login', 'Auth\LoginController@authenticate');
    Route::post('/signup', 'Auth\RegisterController@signup');

    Route::get('/items', 'ItemController@publicIndex');
    Route::get('/items/{item}', 'ItemController@publicShow');

    Route::post('/newUser', 'Auth\RegisterController@create');
    Route::post('/sendPassword', 'Auth\ForgotPasswordController@sendPasswordMail');
    Route::post('/resetPassword', 'Auth\ForgotPasswordController@resetPassword');

    Route::group(['middleware' => 'jwt.auth', 'prefix' => 'restricted'], function () {
        Route::post('/check', 'Auth\LoginController@checkToken');
        Route::post('/logout', 'Auth\LoginController@logout');
        Route::get('/dashboard', 'DashboardController@get');

        Route::post('/admin/invite', 'Auth\RegisterController@invite');

        Route::get('/users', 'UserController@index');
        Route::get('/users/me', 'UserController@showMe');
        Route::post('/users/{user}', 'UserController@update');
        Route::post('/users/{user}/disable', 'UserController@disable');
        Route::post('/users/{user}/enable', 'UserController@enable');

        Route::get('/items', 'ItemController@index');
        Route::post('/items', 'ItemController@store');
        Route::put('/items/{item}', 'ItemController@update');
        Route::get('/items/{item}', 'ItemController@show');
        Route::get('/items/{item}/history', 'ItemController@history');
        Route::post('/items/{item}/defective', 'ItemController@defective');
        Route::post('/items/{item}/missing', 'ItemController@missing');
        Route::post('/items/{item}/available', 'ItemController@available');
        Route::post('/items/{item}/use', 'ItemController@useMaterial');
        Route::post('/items/{item}/restock', 'ItemController@restockMaterial');

        Route::get('/places', 'PlaceController@index');
        Route::post('/places', 'PlaceController@store');
        Route::put('/places/{place}', 'PlaceController@update');
        Route::delete('/places/{place}', 'PlaceController@destroy');

        Route::get('/categories', 'CategoryController@index');
        Route::post('/categories', 'CategoryController@store');
        Route::put('/categories/{category}', 'CategoryController@update');
        Route::delete('/categories/{category}', 'CategoryController@destroy');

        Route::get('/rentals', 'RentalController@index');
        Route::post('/rentals', 'RentalController@store');
        Route::put('/rentals/{rental}', 'RentalController@update');
        Route::get('/rentals/{rental}', 'RentalController@show');
        Route::post('/rentals/{rental}/return', 'RentalController@returnItem');
        Route::post('/rentals/{rental}/lost', 'RentalController@lostItem');

    });
});