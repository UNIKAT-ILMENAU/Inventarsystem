'use strict';

/* Services / RESTful 
Description: Handles all requests to the server.
*/

var invServices = angular.module('invServices', ['ngResource']);

//==============================
//Rest factory
//==============================
invServices.factory('REST', ['$resource',
  function($resource){
    return $resource('/api/v1/item/:ListItemId', {}, {
      //All item informations
      query: {method:'GET', params:{ListItemId: 'allItems'}, isArray:true},
      //Detail information of the selected item
      detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:true},
      //typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true}	testing
    });
  }]);
  
