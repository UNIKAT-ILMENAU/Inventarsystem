'use strict';

/* Services / RESTful 
Description: Handles all list GET requests to the server.
*/

var invServices = angular.module('invServices', ['ngResource', 'ngStorage']);

//==============================
//Rest factory
//==============================
invServices.factory('REST', ['$resource',
function($resource){
  return $resource('/api/v1/restricted/:ListItemId', {}, {
    //All item information
    query: {method:'GET', params:{ListItemId: 'item/allItems'}, isArray:true},
    //Detail information of the selected item
    detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //Detail Place information of the selected item as an string
    detailPlaceLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //Detail information of the selected item
    historyLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //All rentals information
    allRental: {method:'GET', params:{ListItemId: 'rental/AllRentals'}, isArray:true},
    //All open rentals information
    allOpenRental: {method:'GET', params:{ListItemId: 'rental/OpenRentals'}, isArray:true},
    //Detail user information of the selected rental
    detailRentalUserLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //Detail item information of the selected rental
    detailRentalItemLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true}
    //typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true} //not included right now
  });
}]);

//puts JTW-Token in Http-Header
invServices.factory('tokenInterceptor', tokenInterceptor);
function tokenInterceptor($window){
	return{
		request: function(config){
			if($window.localStorage.token){
				config.headers.Authorization = 'Bearer ' + $window.localStorage.token;
			}
			return config;
		}
	}
}


//returns JWT-Claims (User-nr, session, etc.) by using: token.getTokenClaims();
invServices.factory('token', tokenClaims);
function tokenClaims($window){

	//fills in bytes, replaces illegal symbols, important for decoding 
	function urlBase64Decode(str) {
           var output;
           output = str.replace('-', '+');
           output = output.replace('_', '/');

           switch (output.length % 4) {
               case 0:
                   break;
               case 2:
                   output += '==';
                   break;
               case 3:
                   output += '=';
                   break;
               default:
                   throw 'Illegal base64url string';
           }
           //'atob()' decodes base64
           return window.atob(output);
    }

    function getClaims() {
        var token = $window.localStorage.token;
        var user = {};
        if (typeof token !== 'undefined') {
        	//get claims-part of token
            var encoded = token.split('.')[1];
            user = JSON.parse(urlBase64Decode(encoded));
        }
        return user;
    }

    var tokenClaims = getClaims();

    return {   
        getTokenClaims: function () {
           return tokenClaims;
       }
    };
}

//=========================================================
//Rest factory for all GET requests of places & categories
//used in CategoryCtrl, PlaceCtrl, CreateCtrl, ItemEditCtrl
//=========================================================
invServices.factory('dataFactory', ['$http',function($http){
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
    dataFactory.getAllPlaces = function(){
      return  $http.get('/api/v1/restricted/place/allPlace').then(function(response){
        allPlaces = response.data;

        //call functions to format query for rendering in html-template as nested list
        placeResult = _queryTreeSort({q: allPlaces});
        placeTree = _makeTree({q: placeResult});
        return placeTree;
      });
    }

    //GET all categories from server
    dataFactory.getAllCategories = function(){
      return $http.get('/api/v1/restricted/category/allCategory').then(function(response){
        allCategories = response.data;

        //call functions to format query for rendering in html-template as nested list
        categoryResult = _queryTreeSort({q: allCategories});
        categoryTree = _makeTree({q: categoryResult});
        return categoryTree;
      });
    }

 return dataFactory;
}]);
