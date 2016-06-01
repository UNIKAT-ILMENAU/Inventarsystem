'use strict';

/* Services / RESTful */

var invServices = angular.module('invServices', ['ngResource', 'ngStorage']);

invServices.factory('REST', ['$resource',
  function($resource){
    return $resource('json/:ListItemId.json', {}, {
      query: {method:'GET', params:{ListItemId: 'data'}, isArray:true},
      detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:false}, //$resource='@incomingdata'
      typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true}
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
