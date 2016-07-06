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

//Startpage
Route::get('/', function () {
    return view('welcome');
});

//Should imported in the Middleware
Route::post('/send', 'EmailController@send');

//// Item Controller
//Item Information API - Public
Route::get('/api/v1/item/allItems', ['uses' =>'ItemController@showAllItems']);
Route::get('/api/v1/item/allIds', ['uses' =>'ItemController@showAllIds']);
Route::get('/api/v1/item/{id}', ['uses' =>'ItemController@SingleItem']);
Route::get('/api/v1/item/details/{id}', ['uses' =>'ItemController@SingleDetailItem']);
Route::get('/api/v1/getUserId', ['uses' =>'AuthenticateController@getUserIdFromToken']);
Route::post('/api/v1/resetPassword', ['uses' =>'AdminController@changeForgottenPassword']);
Route::post('/api/v1/admin/SendPasswordEmail', ['uses' =>'AdminController@sendForgottenMailToken']);
Route::post('/api/v1/restricted/admin/invite', ['uses' =>'AdminController@invite']);

//JWT AUTH
Route::post('/api/v1/login', ['uses' =>'AuthenticateController@createToken']);
Route::get('/api/v1/check', ['uses' =>'AuthenticateController@checkAuth']);
Route::get('/api/v1/test', ['uses' =>'AuthenticateController@Testing']);

//Testing
Route::post('/api/v1/admin/create', ['uses' =>'AdminController@store']);

/* UNCOMMNET TO USE THE ROUTES WITHOUT THE MIDDLEWARE

//Dashboard
Route::get('/api/v1/restricted/dashboard/Items', ['uses' =>'ItemController@ItemCount']);

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
Route::get('/api/v1/restricted/place/search/{id}', ['uses' =>'PlaceController@PlaceRoute']);
Route::get('/api/v1/restricted/place', ['uses' =>'PlaceController@showP']);
Route::get('/api/v1/restricted/place/child/{id}', ['uses' =>'PlaceController@showC']);

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
Route::get('/api/v1/restricted/rental/SingleRentalsItems/{id}', ['uses' =>'RentalController@showSingleRentalsItems']);

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


*/

//Middleware Checking

//before opening the api, the middleware will check the token

//Route::post('/api/v1/test', ['uses' =>'AuthenticateController@checkAuth']); <-- Middleware Testing Route
Route::group(['middleware' => 'JWTCheck'], function () {
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

	//ACTIVATE HERE
	// Route::post('/api/v1/restricted/admin/create', ['uses' =>'AdminController@store']);
	Route::post('/api/v1/restricted/admin/deactivate/{id}', ['uses' =>'AdminController@AdminDeactivate']);
	Route::post('/api/v1/restricted/admin/changePassword', ['uses' =>'AdminController@changePasswordFromCurrentUser']);

	//Token must sent in Authorizaton to this address ***NEW***
	Route::post('/api/v1/logout', ['uses' =>'AuthenticateController@logout']);


	//// User Controller
	Route::get('/api/v1/restricted/user/allUser', ['uses' =>'UserController@showAllUser']);
	Route::get('/api/v1/restricted/user/{id}', ['uses' =>'UserController@showDetailUser']);

	Route::post('/api/v1/restricted/user/create', ['uses' =>'UserController@store']);
	Route::post('/api/v1/restricted/user/update/{id}', ['uses' =>'UserController@UserUpdate']);


});
