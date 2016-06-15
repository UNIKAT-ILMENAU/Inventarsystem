
'use strict';

/* App Module */

var invApp = angular.module('invApp', ['ngRoute', 'invControllers', 'invServices']);

//==============================
//Route definition
//==============================
invApp.config(['$routeProvider',  
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'html/list.html',    //public list route  
        controller: 'ListCtrl'
      }).
      when('/listData/:ListItemId', {
        templateUrl: 'html/detail.html',  //public detailview route
        controller: 'DetailCtrl'
      }).
      otherwise({
        redirectTo: '/'                                  
      });
  }]);

