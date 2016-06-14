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
    //All item informations
    query: {method:'GET', params:{ListItemId: 'item/allItems'}, isArray:true},
    //Detail information of the selected item
    detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //Detail information of the selected item
    historyLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
    //All rental informations
    allRental: {method:'GET', params:{ListItemId: 'item/allRental'}, isArray:true},
    //Detail information of the selected rental
    detailRentalLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true}
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
