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

/////////// Item Controller

//===================================================
//                 Public
//===================================================

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

Route::get('/api/v1/item/allItems', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/item/allIds', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/item/{id}', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/item/details/{id}', function () {
    //
    return view('welcome');
});

//===================================================
//                    Restricted 
//===================================================

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

Route::get('/api/v1/restricted/item/allItems', function () {
    //
    return view('welcome');
});


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

Route::get('/api/v1/restricted/item/allIds', function () {
    //
    return view('welcome');
});


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

Route::get('/api/v1/restricted/item/{id}', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/restricted/item/details/{id}', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/restricted/item/getHistory/{id}', function () {
    //
    return view('welcome');
});


//===================================================
//         Item Create/ Update /Deactivate
//===================================================

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

Route::post('/api/v1/restricted/device/create', function () {
    //Has to be implemented
    return view('welcome');

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

Route::post('/api/v1/restricted/material/create', function () {
    //Has to be implemented
    return view('welcome');

/**
 * @api {post} /api/v1/restricted/device/update/{id} Update a Device
 * @apiName UpdateDevice
 * @apiGroup Restricted Item
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "name": "Phillips screwdriver",
            "state": 0,
            "description"=> "description screwdriver",
            "place": 5,
            "category": 2,
            "comment": "Updated a Srewdriver",
            "createdbyid" : 1
 *     }
 *
 * @apiParam {String} [name]                    Enter the name 
 * @apiParam {integer} [state]                  Enter a state
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

Route::post('/api/v1/restricted/device/update/{id}', function () {
    //Has to be implemented
    return view('welcome');    

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
 * @apiParam {double} [criticalstoragevalue]      Enter a critical storagevalue
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

Route::post('/api/v1/restricted/material/update/{id}', function () {
    //Has to be implemented
    return view('welcome'); 
/**
 * @api {delete} /api/v1/restricted/item/deactivate/{id} Deacticate an Item
 * @apiName DeacticateItem
 * @apiGroup Restricted Item
 *
 * @apiParamExample {json} Request-Example:
 *     {
            "createdbyid": 1,
            "comment": "Created a srew"
 *     }
 * @apiParam {String} comment                      Enter a comment 
 * @apiParam {integer} createdbyid                 Enter the ID of the creator
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

Route::delete('/api/v1/restricted/item/deactivate/{id}', function () {
    //Has to be implemented
    return view('welcome');
});

//===================================================
//            Comment REQUEST / UPDATE
//===================================================

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

Route::get('/api/v1/restricted/comment/{id}', function () {
    //
    return view('welcome');
});

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

Route::post('/api/v1/restricted/comment/update/{id}', function () {
    //Has to be implemented
    return view('welcome');

//===================================================
//          Place REQUEST / CREATE / UPDATE 
//===================================================

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

Route::get('/api/v1/restricted/place/allPlace', function () {
    //
    return view('welcome');
});

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

Route::get('/api/v1/restricted/place/{id}', function () {
    //
    return view('welcome');
});

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

Route::post('/api/v1/restricted/place/create', function () {
    //Has to be implemented
    return view('welcome');

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

Route::post('/api/v1/restricted/place/update/{id}', function () {
    //Has to be implemented
    return view('welcome');


//===================================================
//   Category REQUEST / CREATE / UPDATE / DELETE
//===================================================

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

Route::get('/api/v1/restricted/category/allCategory', function () {
    //
    return view('welcome');
});


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

Route::get('/api/v1/restricted/category/{id}', function () {
    //
    return view('welcome');
});

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

Route::post('/api/v1/restricted/category/create', function () {
    //Has to be implemented
    return view('welcome');

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

Route::post('/api/v1/restricted/category/update/{id}', function () {
    //Has to be implemented
    return view('welcome');


//===================================================
//               Event REQUEST / CREATE
//===================================================    

/**
 * @api {get} /api/v1/restricted/event/AllEvents Request all Events
 * @apiName GetEvent
 * @apiGroup Restricted Event
 *
 * @apiSuccess {Object/Array} JSON Object form the Item with a special Id
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
              [
                "ID": 1,
                "Name": "CreateItem",
                "Description": "Create a new Item",
                "EventValue": null,
                "CreatedByID": 1
              ],
              [
                "ID": 2,
                "Name": "DeactivateItem",
                "Description": "Deactivate an item",
                "EventValue": null,
                "CreatedByID": 1
              ],
 *     }
 *
 * @apiError EventNotFound               No Event were not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "1"
 *     }
 */

Route::get('/api/v1/restricted/event/AllEvents', function () {
    //
    return view('welcome');
});


/**
 * @api {post} /api/v1/restricted/event/6 Use a Material
 * @apiName EventUseMaterial
 * @apiGroup Restricted Event
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "itemid": 4,
            "amount": 200,
            "createdbyid": 3
 *     }
 *
 * @apiParam {integer} itemid                  Enter the item id
 * @apiParam {integer} amount                  Enter the amount 
 * @apiParam {integer} createdbyid             Enter the id of the creator
 *

 * @apiSuccess {Object/Array}                     JSON Object with a success message
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       item_id;
 *     } 
 *
 * @apiError EventNotCreated                   Event could not be created
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "42"
 *     }
 */

Route::post('/api/v1/restricted/event/6', function () {
    //Has to be implemented
    return view('welcome');

/**
 * @api {post} /api/v1/restricted/event/7     Refill a Material
 * @apiName EventRefillMaterial
 * @apiGroup Restricted Event
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "itemid": 4,
            "amount": 200,
            "createdbyid": 3
 *     }
 *
 * @apiParam {integer} itemid                  Enter the item id
 * @apiParam {integer} amount                  Enter the amount 
 * @apiParam {integer} createdbyid             Enter the id of the creator
 *

 * @apiSuccess {Object/Array}                  JSON Object with a success message
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       item_id;
 *     } 
 *
 * @apiError EventNotCreated                   Event could not be created
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "42"
 *     }
 */

Route::post('/api/v1/restricted/event/7', function () {
    //Has to be implemented
    return view('welcome');

/**
 * @api {post} /api/v1/restricted/event/8     Set Device as defect
 * @apiName EventDeviceDefective
 * @apiGroup Restricted Event
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "itemid": 4,
            "comment": "This is a comment",
            "createdbyid": 3
 *     }
 *
 * @apiParam {integer} itemid                  Enter the item id
 * @apiParam {string} comment                  Enter the comment
 * @apiParam {integer} createdbyid             Enter the id of the creator
 *

 * @apiSuccess {Object/Array}                  JSON Object with a success message
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       item_id;
 *     } 
 *
 * @apiError EventNotCreated                   Event could not be created
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "42"
 *     }
 */

Route::post('/api/v1/restricted/event/8', function () {
    //Has to be implemented
    return view('welcome');

/**
 * @api {post} /api/v1/restricted/event/9     Marks Item as lost
 * @apiName EventItemLost
 * @apiGroup Restricted Event
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "itemid": 4,
            "comment": 200,
            "createdbyid": 3
 *     }
 *
 * @apiParam {integer} itemid                  Enter the item id
 * @apiParam {string} comment                  Enter the comment 
 * @apiParam {integer} createdbyid             Enter the id of the creator
 *

 * @apiSuccess {Object/Array}                  JSON Object with a success message
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       item_id;
 *     } 
 *
 * @apiError EventNotCreated                   Event could not be created
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "42"
 *     }
 */

Route::post('/api/v1/restricted/event/9', function () {
    //Has to be implemented
    return view('welcome');

/**
 * @api {post} /api/v1/restricted/event/10     Sell a Material
 * @apiName EventSellMaterial
 * @apiGroup Restricted Event
 * 
 * @apiParamExample {json} Request-Example:
 *     {
            "itemid": 4,
            "price": 5,
            "amount": 200,
            "createdbyid": 3
 *     }
 *
 * @apiParam {integer} itemid                  Enter the item id
 * @apiParam {integer} price                   Enter the price
 * @apiParam {string} comment                  Enter the comment 
 * @apiParam {integer} createdbyid             Enter the id of the creator
 *

 * @apiSuccess {Object/Array}                  JSON Object with a success message
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       item_id;
 *     } 
 *
 * @apiError EventNotCreated                   Event could not be created
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "error": "42"
 *     }
 */

Route::post('/api/v1/restricted/event/10', function () {
    //Has to be implemented
    return view('welcome');

///////////////// Restricted End
//////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////
//Startseite
Route::get('/', function () {
    return view('welcome');
});

//PUBLIC OUTPUTS - NOT FINISHED - WORKING ON CONTROLLER
Route::get('api/item', 'testing@index');
Route::get('api/item/{id}', 'testing@item');
Route::get('api/item/select{firstid}/end{endid}', 'testing@select');



//PRIVAT OUTPUTS - NOT FINISHED - WORKING ON CONTROLLER - ACTUALLY WITHOUT JWT CHECKING



Route::get('restricted/api/rentalByID/{id}', function ($id) {
	//CONTROLLER IMPLEMENTATION
	//!JWT IMPLEMENTATION!

	/*Problems:
	We will get an Item_ID and we have the SQL Quary the Item Name
	We should give back an Rental Items Array
	We have to check the Material (is it sold or rented? should we send it?)
		Calculating the AMOUNT with the COSTS for the PRICE?
	*/

    return response()->json(
            ['RentalID' => $id,
            'User_FirstName' => 'Max',
            'User_LastName' => ' Mustermann',
            'Member_FirstName' => 'Admin',
            'Member_LastName' => 'Musteradmin',
            'Rental_Date' => '2000-10-10',
            'Rental_State' => 3,
            'Rental_Devices' => 'Item_Name, Item_Name',
            'Rental_Materials' => 'Item_Name x AMOUNT at the PRICE',
            'End_Date' => '2000-10-15'
            ]);
});




//Controller Testing (Http/Controllers/testing.php)
//hier habe ich beispielsweise einen testing controller angelegt. Über die URL/controller
//wird die Funktion test im controller testing aufgerufen
Route::get('controller', 'testing@test');

Route::get('json', 'testing@json_test');

//URL/hallo
Route::get('hallo', function () {
    echo "Hallo Welt";
});

//URL/hallo/{name}
Route::get('hallo/{name}', function ($name) {
    echo "Hallo ". $name;
});

//Weiterführend

//Verschiedene Routen
/*
Route::get 			- wird verwndet um ein Item zu lesen
Route::post 		- wird verwendet um ein Item zu erstellen
Route::put 			- wird verwendet um ein Item up zu daten
Route::delete 		- wird verwendet um ein Item zu löschen
*/

