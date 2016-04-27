'use strict';

/* App Module */

var invApp = angular.module('invApp', [
  'ngRoute', 'invControllers', 'invFilters', 'invServices'
]);

invApp.config(['$routeProvider',    //route definitions
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'html/list.html',    
        controller: 'ListCtrl'
      }).
      when('/listData/:ListItemId', {
        templateUrl: 'html/detail.html',
        controller: 'DetailCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

