'use strict';

/* Services / RESTful */

var invServices = angular.module('invServices', ['ngResource']);

invServices.factory('REST', ['$resource',
  function($resource){
    return $resource('json/:ListItemId.json', {}, {
      query: {method:'GET', params:{ListItemId: 'data'}, isArray:true},
      detailLoad: {method:'GET', params:{ListItemId: $resource}, isArray:false} //$resource='@incomingdata'
    });
  }]);

