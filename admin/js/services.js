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


//==============================================
//TokenInterceptor. injects token in http-header
//==============================================
invServices.factory('tokenInterceptor', tokenInterceptor);
function tokenInterceptor($localStorage){
	return{
		request: function(config){
			if($localStorage.token){
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
function authCheck($http, $location, $localStorage){

  function validate(){  
    $http({
        method: 'POST', 
        url: '/api/v1/check', 
        data: $localStorage.token})
      .then(function(response){

        //check if token isn't valid
        if(response.data != $localStorage.token){
          $location.path("/login");
        } 
      }); 
  }

  return{
    check: function(){
      validate();    
    }       
  }
}
