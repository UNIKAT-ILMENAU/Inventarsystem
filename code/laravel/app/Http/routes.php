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

//Should imported in the Middleware
Route::post('/send', 'EmailController@send');


//======================
//   Item Controller
//======================

//================================
// Item Information API - Public
// Uses: ItemController
//================================

/**
 * @api {get} /api/v1/item/allItems Request all Item information
 * @apiName GetAllItem
 * @apiGroup Public Item
 *
 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     { [
 *       "Id": 1,
 *       "Name": "Phillips screwdriver",
 *       "State": 1,
 *       "Category": "srewdriver",
 *       "BuildType": NULL,
 *       "Saleprice": 19.99,
 *       "StorageValue": "NULL",
 *       "material_id": 1               // material_id = 1 -> Device; material_id != 1 -> Material
 *       ],
 *       ["Id": 2,
 *       "Name": "Claw hammer",
 *       "State": 1,
 *       "Category": "Handtools",
 *       "BuildType": null,
 *       "Saleprice": 5,
 *       "StorageValue": null,
 *       "material_id": 1
 *       ]
 *     }
 *
 * @apiError ItemNotFound The id of the Item was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "1"
 *     }
 */

Route::get('/api/v1/item/allItems', ['uses' =>'ItemController@showAllItems']);

/**
 * @api {get} /api/v1/item/allIds Request all Item IDs
 * @apiName GetItemIDs
 * @apiGroup Public Item
 *
 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     { 
             1,
             2,
             3,
             4,
             5,
             6,
             7,
             8,
             9,
             10
       }
 *
 * @apiError ItemNotFound The id of the Item was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "1"
 *     }
 */

Route::get('/api/v1/item/allIds', ['uses' =>'ItemController@showAllIds']);

/**
 * @api {get} /api/v1/item/{id} Request a single Item information
 * @apiName GetSingleItem
 * @apiGroup Public Item
 *
 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     { 
 *       [
             "Id": 1,
             "Name": "Phillips screwdriver",
             "State": 1,
             "Category": "screwdriver",
             "BuildType": null,
             "Saleprice": 3,
             "StorageValue": null
         ]
       }
 *
 * @apiError ItemNotFound The id of the Item was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "1"
 *     }
 */

Route::get('/api/v1/item/{id}', ['uses' =>'ItemController@SingleItem']);

/**
 * @api {get} /api/v1/item/details/{id} Request a single Item Detail information
 * @apiName GetDetailSingleItem
 * @apiGroup Public Item
 *
 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     { 
 *       [
             "Id": 1,
             "Name": "Phillips screwdriver",
             "State": 1,
             "Category": "screwdriver",
             "BuildType": null,
             "Description": "description screwdriver",
             "Saleprice": 3,
             "UoM": null,
             "UoM_short": null,
             "StorageValue": null,
             "material.id": 1
         ]
       }
 *
 * @apiError ItemNotFound The id of the Item was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "1"
 *     }
 */

Route::get('/api/v1/item/details/{id}', ['uses' =>'ItemController@SingleDetailItem']);

//==============================
// Invite Admin API
//==============================

Route::get('/api/v1/getUserId', ['uses' =>'AuthenticateController@getUserIdFromToken']);
Route::post('/api/v1/resetPassword', ['uses' =>'AdminController@changeForgottenPassword']);
Route::post('/api/v1/admin/SendPasswordEmail', ['uses' =>'AdminController@sendForgottenMailToken']);

//JWT AUTH Functions
Route::post('/api/v1/login', ['uses' =>'AuthenticateController@createToken']);
Route::post('/api/v1/check', ['uses' =>'AuthenticateController@checkAuth']);
Route::post('/api/v1/admin/create', ['uses' =>'AdminController@store']);

//Middleware Checking
//before opening the api, the middleware will check the token

//==================================
//				Restricted
//==================================

Route::group(['middleware' => 'JWTCheck'], function () {
	

	//==============================
	// Dashboard
	// Uses: ItemController
	//==============================
	/**
	 * @api {get} /api/v1/restricted/dashboard/Items Request Dashboard information
	 * @apiName GetDashboardInformation
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	          [
	            10, //all items
	            0,	//all items that are not available
	            5,  //all devices
	            5,  //all materials
	            0,  //all items that are currently missing
	            6,  //all items public visible
	            4,  //all items not public visible
	            0,  //all open rentals
	            0,  //all closed rentals
	            0,  //all rented items
	            0,  //all rented devices
	            5,  //all admins
	            5,  //all places
	            5   //all categorys
	          ]
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */

	Route::get('/api/v1/restricted/dashboard/Items', ['uses' =>'ItemController@ItemCount']);

	//====================================
    // Item Information API - Restricted
    // Uses: ItemController
    //====================================

    //===========
    // ITEM GET
    //===========

	 /**
	 * @api {get} /api/v1/restricted/item/allItems Request all Item information
	 * @apiName GetAllItem
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	          [
	            "Id": 1,
	            "Name": "Phillips screwdriver",
	            "State": 1,
	            "Category": "screwdriver",
	            "BuildType": null,
	            "StorageValue": null,
	            "CriticalStorageValue": null,
	            "Place": "Locker 5",
	            "material_id": 1
	            "PublicVisible": 1,
	            "Deactivated": 0,
	            "Created_at": "2016-04-03",
	            "Updated_at": "2016-04-04",
	            "Saleprice": 3.50
	          ],
	          [
	            "Id": 2,
	            "Name": "Claw hammer",
	            "State": 1,
	            "Category": "Handtools",
	            "BuildType": null,
	            "StorageValue": null,
	            "CriticalStorageValue": null,
	            "Place": "Locker 5",
	            "material_id": 1,
	            "PublicVisible": 1,
	            "Deactivated": 0,
	            "Created_at": "2016-04-03",
	            "Updated_at": "2016-04-04",
	            "Saleprice": 3.50
	          ]
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/item/allItems', 'ItemController@RestrictedshowAllItems');

	/**
	 * @api {get} /api/v1/restricted/item/allIds Request all Item IDs
	 * @apiName GetAllItemIDs
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	            1,
	            2,
	            3,
	            4,
	            5,
	            6,
	            7,
	            8,
	            9,
	            10
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/item/allIds', ['uses' =>'ItemController@RestrictedshowAllIds']);

	/**
	 * @api {get} /api/v1/restricted/item/{id} Request single Item information
	 * @apiName GetSingleItem
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	            [
	            "Id": 1,
	            "Name": "Phillips screwdriver",
	            "State": 1,
	            "Category": "screwdriver",
	            "BuildType": null,
	            "StorageValue": null,
	            "CriticalStorageValue": null,
	            "Place": "Locker 5",
	            "PublicVisible": 1,
	            "Deactivated": 0,
	            "Created_at": "2016-04-03",
	            "Updated_at": "2016-04-03",
	            "Saleprice": 3.50
	            ]
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/item/{id}', ['uses' =>'ItemController@RestrictedSingleItem']);

	/**
	 * @api {get} /api/v1/restricted/item/details/{id} Request single Item Detailinformation
	 * @apiName GetDetailSingleItem
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	            [
	                "Id": 1,
	                "Name": "Phillips screwdriver",
	                "State": 1,
	                "Place": "Locker 5",
	                "Category": "screwdriver",
	                "BuildType": null,
	                "Description": "description screwdriver",
	                "UoM": null,
	                "UoM_short": null,
	                "PublicVisible": 1,
	                "Deactivated": 0,
	                "StorageValue": null,
	                "CriticalStorageValue": null,
	                "Created_at": "2016-04-03",
	                "Updated_at": "2016-04-03",
	                "Saleprice": 3.50
	            ]
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/item/details/{id}', ['uses' =>'ItemController@RestrictedSingleDetailItem']);

	/**
	 * @api {get} /api/v1/restricted/item/getHistory/{id} Request history information 
	 * @apiName GetHistory
	 * @apiGroup Restricted Item
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	            [
	                "CommentID": 1,
	                "CreatedByID": 1,
	                "Event_ID": 1,
	                "created_at": null
	            ]
	 *     }
	 *
	 * @apiError ItemNotFound The id of the Item was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/item/getHistory/{id}', ['uses' =>'ItemController@History']);

	//============
    //	ITEM POST
    //============
	
	/**
	 * @api {post} /api/v1/restricted/device/create Create a new Device
	 * @apiName CreateDevice
	 * @apiGroup Restricted Item
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	 *          "name": "srewdriver",
	 *          "state": 1,
	 *          "createdbyid": 1,
	 *          "place": 2,
	 *          "category": 3,
	 *          "description": "Description srewdrivee",
	 *          "comment": "Comment Srewdriver",
	 *          "visible": 1
	 *     }
	 *
	 * @apiParam {String} name                      Enter the name 
	 * @apiParam {integer} [state]                  Enter the state
	 * @apiParam {integer} createdbyid              Enter the ID of the Creator
	 * @apiParam {integer} place                    Enter the ID of the start place
	 * @apiParam {integer} category                 Enter the ID of the start category
	 * @apiParam {String} [description]             Enter a description
	 * @apiParam {integer} comment                  Enter a attachment ID
	 * @apiParam {boolean} visible                  1 = visible 0 = not visible in the public list 

	 * @apiSuccess {Object/Array}                   JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError ItemNotCreated                    Item could not be created
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/device/create', ['uses' =>'ItemController@DeviceStore']);

	/**
	 * @api {post} /api/v1/restricted/material/create Create a new Material
	 * @apiName CreateMateral
	 * @apiGroup Restricted Item
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name":"screws",
	            "state": 1,
	            "description": "description screwdriver",
	            "buildtype": "cross",
	            "visible": 1,
	            "createdbyid": 1,
	            "place": 5,
	            "category": 2,
	            "saleprice": 3.50,
	            "uom": "Kilogram",
	            "uom_short": "kg",
	            "storagevalue": 300,
	            "criticalstoragevalue": 200,
	            "comment": "Created a srew"
	 *     }
	 *
	 * @apiParam {String} name                      Enter the name 
	 * @apiParam {integer} [state]                  Enter the state
	 * @apiParam {String} [description]               Enter a description
	 * @apiParam {String} [buildtype]                 Enter a buildtype
	 * @apiParam {boolean} visible                  1 = visible 0 = not visible in the public list 
	 * @apiParam {integer} createdbyid              Enter the ID of the Creator
	 * @apiParam {integer} place                    Enter the ID of the start place
	 * @apiParam {integer} category                 Enter the ID of the start category
	 * @apiParam {double} [saleprice]                 Enter a saleprice
	 * @apiParam {string} [uom]                       Enter a unit of measurement
	 * @apiParam {string} [uom_short]                 Enter a unit of measurement (short)
	 * @apiParam {double} storagevalue              Enter a storagevalue
	 * @apiParam {double} [criticalstoragevalue]      Enter a unit of measurement
	 * @apiParam {string} comment                   Enter a comment

	 * @apiSuccess {Object/Array}                   JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError ItemNotCreated                    Item could not be created
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/material/create', ['uses' =>'ItemController@MaterialStore']);

	/**
	 * @api {post} /api/v1/restricted/device/update/{id} Update a Device
	 * @apiName UpdateDevice
	 * @apiGroup Restricted Item
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name": "Phillips screwdriver",
	            "description"=> "description screwdriver",
	            "place": 5,
	            "category": 2,
	            "comment": "Updated a Srewdriver",
	            "createdbyid" : 1
	 *     }
	 *
	 * @apiParam {String} [name]                      Enter the name 
	 * @apiParam {String} [description]             Enter a description
	 * @apiParam {integer} createdbyid              Enter the ID of the Creator
	 * @apiParam {integer} [place]                  Enter the ID of the start place
	 * @apiParam {integer} [category]               Enter the ID of the start category
	 * @apiParam {string} comment                   Enter a comment

	 * @apiSuccess {Object/Array}                   JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError ItemNotUpdated                   Item could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/device/update/{id}', ['uses' =>'ItemController@DeviceUpdate']);

	/**
	 * @api {post} /api/v1/restricted/material/update/{id} Update a Material
	 * @apiName UpdateMaterial
	 * @apiGroup Restricted Item
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name":"screws",
	            "state": 1,
	            "description": "description screwdriver",
	            "buildtype": "cross",
	            "visible": 1,
	            "createdbyid": 1,
	            "place": 5,
	            "category": 2,
	            "saleprice": 3.50,
	            "uom": "Kilogram",
	            "uom_short": "kg",
	            "storagevalue": 300,
	            "criticalstoragevalue": 200,
	            "comment": "Created a srew"
	 *     }
	 *
	 * @apiParam {String} [name]                      Enter the name 
	 * @apiParam {integer} [state]                    Enter the state
	 * @apiParam {String} [description]               Enter a description
	 * @apiParam {String} [buildtype]                 Enter a buildtype
	 * @apiParam {boolean} [visible]                  1 = visible 0 = not visible in the public list 
	 * @apiParam {integer} createdbyid                Enter the ID of the Creator
	 * @apiParam {integer} [place]                    Enter the ID of the start place
	 * @apiParam {integer} [category]                 Enter the ID of the start category
	 * @apiParam {double} [saleprice]                 Enter a saleprice
	 * @apiParam {string} [uom]                       Enter a unit of measurement
	 * @apiParam {string} [uom_short]                 Enter a unit of measurement (short)
	 * @apiParam {double} [storagevalue]              Enter a storagevalue
	 * @apiParam {double} [criticalstoragevalue]      Enter a unit of measurement
	 * @apiParam {string} comment                     Enter a comment

	 * @apiSuccess {Object/Array}                   JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError ItemNotUpdated                   Item could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/material/update/{id}', ['uses' =>'ItemController@MaterialUpdate']);

	//===============
    //	ITEM DELETE
    //===============

    /**
	 * @api {delete} /api/v1/restricted/item/deactivate/{id} Deacticate an Item
	 * @apiName DeacticateItem
	 * @apiGroup Restricted Item
	 *
	 *
	 * @apiDescription This function can delete an material or device (item).
	 * You only have to enter the item id in the URL.
	 *
	 *
	 * @apiParam (Login) {Token} Authoriziation    Only logged in users can deactivate items
	 *                                             
	 *                          
	 *
	 * @apiSuccess {Object/Array} JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "Success"
	 *     }
	 *
	 * @apiError ItemNotDeactivated The Item could not be deactivated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": 4
	 *     }
	 */
	Route::delete('/api/v1/restricted/item/deactivate/{id}', ['uses' =>'ItemController@ItemDelete']);

	//====================================
    // Comment - Restricted
    // Uses: CommentController
    //====================================
	
	//===============
    //	 COMMENT GET
    //=============== 

    /**
	 * @api {get} /api/v1/restricted/comment/{id} Request Comment 
	 * @apiName GetComment
	 * @apiGroup Restricted Comment
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	            [
	                "Comment": "2",
	                "created_at": "2016-06-07 13:59:31",
	                "updated_at": "2016-06-08 13:59:31"
	            ]
	 *     }
	 *
	 * @apiError CommentNotFound The id of the Comment was not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/comment/{id}', ['uses' =>'CommentController@getComment']);

	//=================
    //	 COMMENT POST
    //=================

    /**
	 * @api {post} /api/v1/restricted/comment/update/{id} Update a Comment
	 * @apiName UpdateComment
	 * @apiGroup Restricted Comment
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "comment": "This is a comment",
	            "created_at": "2016-06-07 13:59:31",
	            "updated_at": "2016-06-08 13:59:31"
	 *     }
	 *
	 * @apiParam {String} Comment                     Enter the Comment
	 * @apiParam {integer} [state]                    Enter the state

	 * @apiSuccess {Object/Array}                   JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       id;
	 *     } 
	 *
	 * @apiError ItemNotUpdated                   Item could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/comment/update/{id}', ['uses' =>'CommentController@CommentUpdate']);

	//====================================
    // Place - Restricted
    // Uses: PlaceController
    //====================================
	
	//=================
    //	 PLACE GET
    //=================

    /**
	 * @api {get} /api/v1/restricted/place/allPlace Request all Places
	 * @apiName GetAllPlaces
	 * @apiGroup Restricted Place
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	          [
	            "id": 1,
	            "Name": "House M",
	            "BeforeID": null,
	            "CreatedByID": 4,
	            "created_at": null,
	            "updated_at": null
	          ],
	          [
	            "id": 2,
	            "Name": "House L",
	            "BeforeID": null,
	            "CreatedByID": 3,
	            "created_at": "2016-06-07 13:59:31",
	            "updated_at": "2016-06-07 13:59:31"
	          ]
	 *     }
	 *
	 * @apiError PlaceNotFound No places were not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/place/allPlace', ['uses' =>'PlaceController@showAllPlace']);

	/**
	 * @api {get} /api/v1/restricted/place/{id} Request all Places
	 * @apiName GetAllPlaces
	 * @apiGroup Restricted Place
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "Name": "House M",
	                "CreatedByID": 4,
	                "BeforeID": null,
	                "created_at": "2016-06-07 13:59:31",
	                "updated_at": "2016-06-07 13:59:31"
	              ]
	 *     }
	 *
	 * @apiError PlaceNotFound No places were not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/place/{id}', ['uses' =>'PlaceController@getPlace']);

	/**
	 * @api {get} /api/v1/restricted/place/search/{id} Request a Placepath
	 * @apiName GetPlacePath
	 * @apiGroup Restricted Place
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "House L - Room 203 - Locker 5"
	              ]
	 *     }
	 *
	 * @apiError PlaceNotFound No places were not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/place/search/{id}', ['uses' =>'PlaceController@PlaceRoute']);

	/**
	 * @api {get} /api/v1/restricted/place/parents Request parent places
	 * @apiName GetParentsPlaces
	 * @apiGroup Restricted Place
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "id":1,
	                "Name":"House M"
	              ],
	              [
	                "id":2,
	                "Name":"House L"
	              ]
	 *     }
	 *
	 * @apiError ParentPlacesNotFound No parent places were found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/place/parents', ['uses' =>'PlaceController@showP']);

	/**
	 * @api {get} /api/v1/restricted/place/child/{id} Request child of a place
	 * @apiName GetPlaceChild
	 * @apiGroup Restricted Place
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "id":4,
	                "Name":"Room 203"
	              ]
	 *     }
	 *
	 * @apiError ParentPlacesNotFound No parent places were found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/place/child/{id}', ['uses' =>'PlaceController@showC']);

	//=================
    //	 PLACE POST
    //=================

	/**
	 * @api {post} /api/v1/restricted/place/create Create a Place
	 * @apiName CreatePlace
	 * @apiGroup Restricted Place
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name": "House M",
	            "before": 1,
	            "createdbyid": 2
	 *     }
	 *
	 * @apiParam {String}  Name                       Enter Place
	 * @apiParam {integer} [Before]                   Enter the BeforeID 
	 * @apiParam {integer} createdbyid                Enter the ID of the creator
	 *

	 * @apiSuccess {Object/Array}                     JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       place_id;
	 *     } 
	 *
	 * @apiError PlaceNotCreated                      Place could not be created
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/place/create', ['uses' =>'PlaceController@PlaceStore']);

	/**
	 * @api {post} /api/v1/restricted/place/update/{id} Update a Place
	 * @apiName UpdatePlace
	 * @apiGroup Restricted Place
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name": "House M",
	            "before": 1,
	 *     }
	 *
	 * @apiParam {String}  name                       Enter Place
	 * @apiParam {integer} [before]                   Enter the BeforeID 
	 *

	 * @apiSuccess {Object/Array}                     JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError PlaceNotUpdated                     Place could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/place/update/{id}', ['uses' =>'PlaceController@PlaceUpdate']);

	//====================================
    // Category - Restricted
    // Uses: CategoryController
    //====================================
	
	//=================
    //	 PLACE GET
    //=================

    /**
	 * @api {get} /api/v1/restricted/category/allCategory Request all Categories
	 * @apiName GetAllCategory
	 * @apiGroup Restricted Category
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "id": 1,
	                "Name": "Handtools",
	                "Description": "Tools you can use with one hand",
	                "BeforeID": null,
	                "created_at": null,
	                "updated_at": null
	              ],
	              [
	                "id": 2,
	                "Name": "screwdriver",
	                "Description": "Tool to srew soemthing in or out",
	                "BeforeID": 1,
	                "created_at": null,
	                "updated_at": null
	              ]
	 *     }
	 *
	 * @apiError CategoryNotFound               No category were not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/category/allCategory', ['uses' =>'CategoryController@showAllCategory']);

	/**
	 * @api {get} /api/v1/restricted/category/{id} Request single Category information
	 * @apiName GetSingleCategory
	 * @apiGroup Restricted Category
	 *
	 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	              [
	                "Name": "Handtools",
	                "Description": "Tools you can use with one hand",
	                "BeforeID": null,
	                "created_at": null,
	                "updated_at": null
	              ]
	 *     }
	 *
	 * @apiError CategoryNotFound               No category were not found.
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "1"
	 *     }
	 */
	Route::get('/api/v1/restricted/category/{id}', ['uses' =>'CategoryController@getCategory']);

	//=================
    //	 PLACE POST
    //=================

	/**
	 * @api {post} /api/v1/restricted/category/create Create a Category
	 * @apiName CreateCategory
	 * @apiGroup Restricted Category
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name": "House M",
	            "before": 1,
	            "description": "This is Description"
	 *     }
	 *
	 * @apiParam {String}  name                       Enter Place
	 * @apiParam {integer} [before]                   Enter the BeforeID 
	 * @apiParam {integer} [description]              Enter the Description
	 *

	 * @apiSuccess {Object/Array}                     JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       category_Id;
	 *     } 
	 *
	 * @apiError PlaceNotUpdated                     Place could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/category/create', ['uses' =>'CategoryController@CategoryStore']);

	/**
	 * @api {post} /api/v1/restricted/category/update/{id} Update a Category
	 * @apiName UpdateCategory
	 * @apiGroup Restricted Category
	 * 
	 * @apiParamExample {json} Request-Example:
	 *     {
	            "name": "House M",
	            "before": 1,
	            "description": "This is Description"
	 *     }
	 *
	 * @apiParam {String}  [name]                     Enter Place
	 * @apiParam {integer} [before]                   Enter the BeforeID 
	 * @apiParam {integer} [description]              Enter the Description
	 *

	 * @apiSuccess {Object/Array}                     JSON Object with a success message
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       item_id;
	 *     } 
	 *
	 * @apiError CategoryNotUpdated                     Category could not be updated
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "42"
	 *     }
	 */
	Route::post('/api/v1/restricted/category/update/{id}', ['uses' =>'CategoryController@CategoryUpdate']);


	//====================================
    // Rental - Restricted
    // Uses: RentalController
    //====================================
	
	//=================
    //	 RENTAL GET
    //=================

	Route::get('/api/v1/restricted/rental/AllRentals', ['uses' =>'RentalController@showAllRentals']);
	Route::get('/api/v1/restricted/rental/OpenRentals', ['uses' =>'RentalController@showOpenRentals']);
	Route::get('/api/v1/restricted/rental/SingleRentals/{id}', ['uses' =>'RentalController@showSingleRentals']);
	Route::get('/api/v1/restricted/rental/SingleRentalsItems/{id}', ['uses' =>'RentalController@showSingleRentalsItems']);

	//=================
    //	 RENTAL POST
    //=================

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
	Route::post('/api/v1/restricted/admin/invite', ['uses' =>'AdminController@invite']);

	//ACTIVATE HERE
	Route::post('/api/v1/restricted/admin/create', ['uses' =>'AdminController@store']);
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
