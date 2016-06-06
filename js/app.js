
'use strict';

/* App Module */

var invApp = angular.module('invApp', [
  'ngRoute', 'invControllers', 'invServices'
]);

//Route definition
invApp.config(['$routeProvider',  
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'html/list.html',    //inventory public list  
        controller: 'ListCtrl'
      }).
      when('/listData/:ListItemId', {
        templateUrl: 'html/detail.html',  //selected item detailview
        controller: 'DetailCtrl'
      }).
      otherwise({
        redirectTo: '/'                    
      });
  }]);

