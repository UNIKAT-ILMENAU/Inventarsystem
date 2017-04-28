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
        return $resource('api/v1/items/:id', {}, {
            allItems: {method: 'GET', isArray: true},
            detailLoad: {method: 'GET'},
        });
    }]);

invServices.filter('to_trusted', ['$sce', function($sce){
        return function(text) {
            return $sce.trustAsHtml(text);
        };
    }]);