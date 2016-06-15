<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('welcome');
});



//// Item Controller
//Item Information API - Public
Route::get('/api/v1/item/allItems', ['uses' =>'ItemController@showAllItems']);
Route::get('/api/v1/item/allIds', ['uses' =>'ItemController@showAllIds']);
Route::get('/api/v1/item/{id}', ['uses' =>'ItemController@SingleItem']);
Route::get('/api/v1/item/details/{id}', ['uses' =>'ItemController@SingleDetailItem']);

//Item Information API - Restricted
Route::get('/api/v1/restricted/item/allItems', 'ItemController@RestrictedshowAllItems');
Route::get('/api/v1/restricted/item/allIds', ['uses' =>'ItemController@RestrictedshowAllIds']);
Route::get('/api/v1/restricted/item/{id}', ['uses' =>'ItemController@RestrictedSingleItem']);
Route::get('/api/v1/restricted/item/details/{id}', ['uses' =>'ItemController@RestrictedSingleDetailItem']);
Route::get('/api/v1/restricted/item/getHistory/{id}', ['uses' =>'ItemController@History']);

//Item
Route::post('/api/v1/restricted/device/create', ['uses' =>'ItemController@DeviceStore']);
Route::post('/api/v1/restricted/material/create', ['uses' =>'ItemController@MaterialStore']);

Route::post('/api/v1/restricted/device/update/{id}', ['uses' =>'ItemController@DeviceUpdate']);
Route::post('/api/v1/restricted/material/update/{id}', ['uses' =>'ItemController@MaterialUpdate']);

Route::delete('/api/v1/restricted/item/deactivate/{id}', ['uses' =>'ItemController@ItemDelete']);

//// Comment Controller
Route::get('/api/v1/restricted/comment/{id}', ['uses' =>'CommentController@getComment']);

Route::post('/api/v1/restricted/comment/update/{id}', ['uses' =>'CommentController@CommentUpdate']);

//// Place Controller
Route::get('/api/v1/restricted/place/allPlace', ['uses' =>'PlaceController@showAllPlace']);
Route::get('/api/v1/restricted/place/{id}', ['uses' =>'PlaceController@getPlace']);

Route::post('/api/v1/restricted/place/create', ['uses' =>'PlaceController@PlaceStore']);
Route::post('/api/v1/restricted/place/update/{id}', ['uses' =>'PlaceController@PlaceUpdate']);

//// Category Controller
Route::get('/api/v1/restricted/category/allCategory', ['uses' =>'CategoryController@showAllCategory']);
Route::get('/api/v1/restricted/category/{id}', ['uses' =>'CategoryController@getCategory']);

Route::post('/api/v1/restricted/category/create', ['uses' =>'CategoryController@CategoryStore']);
Route::post('/api/v1/restricted/category/update/{id}', ['uses' =>'CategoryController@CategoryUpdate']);

//// Rental Controller
Route::get('/api/v1/restricted/rental/AllRentals', ['uses' =>'RentalController@showAllRentals']);
Route::get('/api/v1/restricted/rental/OpenRentals', ['uses' =>'RentalController@showOpenRentals']);
Route::get('/api/v1/restricted/rental/SingleRentals/{id}', ['uses' =>'RentalController@showSingleRentals']);

Route::post('/api/v1/restricted/rental/create', ['uses' =>'RentalController@store']);
Route::post('/api/v1/restricted/rental/bringBack/{id}', ['uses' =>'RentalController@BringBackSingle']);
Route::post('/api/v1/restricted/rental/bringBackMultiple/{id}', ['uses' =>'RentalController@BringBackMultiple']);
Route::post('/api/v1/restricted/rental/lost/{id}', ['uses' =>'RentalController@ItemLost']);

//// Event Controler
Route::get('/api/v1/restricted/event/AllEvents', ['uses' =>'EventController@showAllEvents']);

Route::post('/api/v1/restricted/event/{id}', ['uses' =>'EventController@doEvent']);





//// Admin Controller
Route::get('/api/v1/restricted/admin/allAdmins', ['uses' =>'AdminController@showAllAdmins']);
Route::get('/api/v1/restricted/admin/{id}', ['uses' =>'AdminController@showDetailAdmins']);

Route::post('/api/v1/restricted/admin/create', ['uses' =>'AdminController@store']);
Route::post('/api/v1/restricted/admin/deactivate/{id}', ['uses' =>'AdminController@AdminDeactivate']);



//// User Controller
Route::get('/api/v1/restricted/user/allUser', ['uses' =>'UserController@showAllUser']);
Route::get('/api/v1/restricted/user/{id}', ['uses' =>'UserController@showDetailUser']);

Route::post('/api/v1/restricted/user/create', ['uses' =>'UserController@store']);
Route::post('/api/v1/restricted/user/update/{id}', ['uses' =>'UserController@UserUpdate']);


//JWT AUTH
Route::post('/api/v1/login', ['uses' =>'AuthenticateController@authenticate']);


