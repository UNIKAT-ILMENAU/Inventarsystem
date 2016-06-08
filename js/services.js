'use strict';

/* Services / RESTful 
Description: Handles all requests to the server.
*/

var invServices = angular.module('invServices', ['ngResource']);

invServices.factory('REST', ['$resource',
  function($resource){
    return $resource('json/:ListItemId.json', {}, {
      query: {method:'GET', params:{ListItemId: 'data'}, isArray:true},
      detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:false}, //$resource='@incomingdata'
      typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true}
    });
  }]);

/*
invServices.factory('REST', ['$resource',
  function($resource){
    return $resource('softwareprojekt.local/api/v1/item/:ListItemId', {}, {
      query: {method:'GET', params:{ListItemId: 'allItems'}, isArray:true},
      detailLoad: {method:'GET', params:{ListItemId: 'details/' + $resource}, isArray:false},
      //typload: {method:'GET', params:{ListItemId: 'typeahead'}, isArray:true}	testing
    });
  }]);

*/