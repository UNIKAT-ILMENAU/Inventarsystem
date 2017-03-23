'use strict';

/* Services / RESTful
 Description: Handles all list GET requests to the server.
 */

var invServices = angular.module('invServices', ['ngResource', 'ngStorage']);

//==============================
//Rest factory
//==============================
invServices.factory('REST', ['$resource',
    function ($resource) {
        return $resource('/api/v1/restricted/:ListItemId', {}, {
            //All item information
            // query: {method: 'GET', params: {ListItemId: 'item/allItems'}, isArray: true},
            //Detail information of the selected item
            // detailLoad: {method: 'GET', params: {ListItemId: $resource}, isArray: true},
            //Detail Place information of the selected item as an string
            // detailPlaceLoad: {method: 'GET', params: {ListItemId: $resource}, isArray: true},
            //Detail information of the selected item
            // historyLoad: {method: 'GET', params: {ListItemId: $resource}, isArray: true},
            //All rentals information
            // allRental: {method: 'GET', params: {ListItemId: 'rental/AllRentals'}, isArray: true},
            //All open rentals information
            // allOpenRental: {method: 'GET', params: {ListItemId: 'rental/OpenRentals'}, isArray: true},
            //Detail user information of the selected rental
            // detailRentalUserLoad: {method: 'GET', params: {ListItemId: $resource}, isArray: true},
            //Detail item information of the selected rental
            // detailRentalItemLoad: {method: 'GET', params: {ListItemId: $resource}, isArray: true},
            //All dashboard information
            // dashboardLoad: {method: 'GET', params: {ListItemId: 'dashboard/Items'}, isArray: true}
            //typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true} //not included right now
        });
    }]);

invServices.factory('ItemResource', ['$resource',
    function ($resource) {
        return $resource('/api/v1/restricted/item/:id/:subpath', {}, {
            allItems: {method: 'GET', params: {id: 'allItems'}, isArray: true},
            detailLoad: {method: 'GET', params: {subpath: "details"}, isArray: true},
            historyLoad: {method: 'GET', params: {subpath: "history"}, isArray: true},
            place: {method: 'GET', url: "/api/v1/restricted/place/search/:id", isArray: true}
        });
    }]);

invServices.factory('DashboardResource', ['$resource',
    function ($resource) {
        return $resource('/api/v1/restricted/dashboard/:data', {}, {
            items: {method: 'GET', params: {data: 'Items'}, isArray: true}
        });
    }]);

invServices.factory('PlaceResource', ['$resource',
function ($resource) {
    return $resource('/api/v1/restricted/place/:id', {}, {

    })

}]);

invServices.factory('RentalResource', ['$resource',
    function ($resource) {
        return $resource('/api/v1/restricted/rental/:action/:id', {}, {
            allRental: {method: 'GET', params: {action: 'AllRentals'}, isArray: true},
            allOpenRental: {method: 'GET', params: {action: 'OpenRentals'}, isArray: true},
            detailRentalUserLoad: {method: 'GET', params: {action: 'SingleRentals'}, isArray: true},
            detailRentalItemLoad: {method: 'GET', params: {action: 'SingleRentalsItems'}, isArray: true},
        })

    }]);


//==============================================
//TokenInterceptor. injects token in http-header
//==============================================
invServices.factory('tokenInterceptor', tokenInterceptor);
function tokenInterceptor($localStorage) {
    return {
        request: function (config) {
            if ($localStorage.token) {
                config.headers.Authorization = 'Bearer ' + $localStorage.token;
            }
            return config;
        }
    }
}


//======================================================================
//authCheck. checks token. used to stop sending views when not logged in
//======================================================================
invServices.factory('authCheck', authCheck);
function authCheck($http, $location, $localStorage) {

    function validate() {
        $http({
            method: 'POST',
            url: '/api/v1/check',
            data: $localStorage.token
        }).then(function (response) {}, function (response) {
            console.log('Auth check failed');
            $location.path("/login");
        });
    }

    return {
        check: function () {
            validate();
        }
    }
}

//=========================================================
//Rest factory for all GET requests of places & categories
//used in CategoryCtrl, PlaceCtrl, CreateCtrl, ItemEditCtrl
//=========================================================
invServices.factory('dataFactory', ['$http', 'tree', function ($http, tree) {
    //obj returned to controller
    var dataFactory = {};
    //var nesseary for GET places-array and nesting array
    var allPlaces = [];
    var placeResult;
    var placeTree;
    //var nesseary for GET categories-array and nesting array
    var allCategories = [];
    var categoryResult;
    var categoryTree;

    //GET all places from server
    dataFactory.getAllPlaces = function () {
        return $http.get('/api/v1/restricted/place/allPlace').then(function (response) {
            allPlaces = response.data;

            //call functions (in tree factory in services.js) to format query for rendering in html-template as nested list
            placeResult = tree.sortTree({array: allPlaces}); //call tree.sortTree
            placeTree = tree.makeTree({array: placeResult}); //call tree.makeTree

            return placeTree;
        });
    }

    //GET all categories from server
    dataFactory.getAllCategories = function () {
        return $http.get('/api/v1/restricted/category/allCategory').then(function (response) {
            allCategories = response.data;

            //call functions (in tree factory in services.js) to format query for rendering in html-template as nested list
            categoryResult = tree.sortTree({array: allCategories}); //call tree.sortTree
            categoryTree = tree.makeTree({array: categoryResult}); //call tree.makeTree

            return categoryTree;
        });
    }

    return dataFactory;
}]);

//=========================================================
//factory for sorting GET requests of places & categories
//used in dataFactory(services.js)
//=========================================================
invServices.factory('tree', function () {
    //obj returned
    var tree = {};

    //Sort a query with id and beforeid so that the rows have the correct order of the tree
    /*sorts query like this:
     [
     {"id": 456, "BeforeID": 123, "name": "Dogs"},
     {"id": 214, "BeforeID": 456, "name": "drilling machine A"},
     {"id": 123, "BeforeID": null, "name": "Tools"},
     {"id": 810, "BeforeID": 456, "name": "drilling machine B"},
     {"id": 919, "BeforeID": 456, "name": "drilling machine C"}
     ]
     to this:
     [
     {"id": 123, "BeforeID": null, "name": "Tools"},
     {"id": 456, "BeforeID": 123, "name": "drilling machine"},
     {"id": 214, "BeforeID": 456, "name": "drilling machine A"},
     {"id": 810, "BeforeID": 456, "name": "drilling machine B"},
     {"id": 919, "BeforeID": 456, "name": "drilling machine C"}
     ] */
    tree.sortTree = function (input) {
        var cfi, e, i, id, output, pid, rfi, ri, thisid, _i, _j, _len, _len1, _ref, _ref1;
        id = input.id || "id";
        pid = input.BeforeID || "BeforeID";
        ri = []; // Root items
        rfi = {}; // Rows from id
        cfi = {}; // Children from id
        output = [];
        _ref = input.array;
        // Setup Indexing
        for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
            e = _ref[i];
            rfi[e[id]] = i;
            if (cfi[e[pid]] == null) {
                cfi[e[pid]] = [];
            }
            cfi[e[pid]].push(input.array[i][id]);
        }
        _ref1 = input.array;
        //Find parents without rows
        for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            e = _ref1[_j];
            if (rfi[e[pid]] == null) {
                ri.push(e[id]);
            }
        }
        //Create the correct order
        while (ri.length) {
            thisid = ri.splice(0, 1);
            output.push(input.array[rfi[thisid]]);
            if (cfi[thisid] != null) {
                ri = cfi[thisid].concat(ri);
            }
        }
        return output;
    };

    //Transform a correctly sorted query (Use sortTree()) with id and beforeid into a tree object
    /* tree becomes nested like his:
     [
     {
     "id": 123,
     "BeforeID": null,
     "name": "Tools",
     "children": [
     {
     "id": 456,
     "BeforeID": 123,
     "name": "drilling machine"
     "children": [
     {
     "id": 214,
     "BeforeID": 456,
     "name": "drilling machine A"
     },
     {
     "id": 810,
     "BeforeID": 456,
     "name": "drilling machine B"
     },
     {
     "id": 919,
     "BeforeID": 456,
     "name": "drilling machine C"
     }
     ]
     }
     ]
     }
     ] */
    tree.makeTree = function (input) {
        var children, e, id, output, pid, temp, _i, _len, _ref;
        id = input.id || "id";
        pid = input.BeforeID || "BeforeID";
        children = input.children || "children";
        temp = {};
        output = [];
        _ref = input.array;
        //Create the tree
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            e = _ref[_i];
            e[children] = [];
            //Add the row to the index
            temp[e[id]] = e;
            //This parent should be in the index
            if (temp[e[pid]] != null) { //This row is a child?
                //Add the child to the parent
                temp[e[pid]][children].push(e);
            } else {
                //Add a root item
                output.push(e);
            }
        }
        return output;
    };

    return tree;
});
