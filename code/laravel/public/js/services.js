'use strict';

/* Services / RESTful 
Description: Handles all requests to the server.
*/

var invServices = angular.module('invServices', ['ngResource']);

//==============================
//Rest factory
//==============================

invServices.factory('ItemResource', ['$resource',
    function ($resource) {
        return $resource('api/v1/item/:id/:subpath', {}, {
            allItems: {method: 'GET', params: {id: 'allItems'}, isArray: true},
            detailLoad: {method: 'GET', params: {subpath: "details"}, isArray: true},
        });
    }]);