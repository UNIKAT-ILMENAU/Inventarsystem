
'use strict';

/* App Module */

var invApp = angular.module('invApp', [
  'ngRoute', 'invControllers', 'invServices'
]);

//tokenInterceptor bugfix: 
//injecting JWT Authentication in http-Header by using factory 
invApp.config(['$httpProvider', function($httpProvider){
  $httpProvider.interceptors.push('tokenInterceptor');
}]);

//Route definition
invApp.config(['$routeProvider',  
  function($routeProvider) {          //######## hier noch $http mit in die funktion eingefügt wieso?
    $routeProvider.
      when('/', {                     //Login
        templateUrl: 'html/login.html',    
        controller: 'loginCtrl'
      }).
      when('/login', {                //Login
        templateUrl: 'html/login.html',
        controller: 'loginCtrl'
      }).
      when('/dashboard', {            //Mainmenu/Dashboard
        templateUrl: 'html/dashboard.html',
        controller: 'DetailCtrl'
      }).
      when('/list', {                 //Itemlist
        templateUrl: 'html/itemlist.html',    
        controller: 'ListCtrl'
      }).
      when('/create_device', {
        templateUrl: 'html/create_device.html', 
        controller: 'DetailCtrl'
      }).
      when('/create_material', {
        templateUrl: 'html/create_material.html', 
        controller: 'DetailCtrl'
      }).
      when('/listData/:ListItemId', {  //ItemDetailView
        templateUrl: 'html/detail.html',
        controller: 'DetailCtrl'
      }).
      when('/borrow', {               //BorrowForm
        templateUrl: 'html/borrow.html',    
        controller: 'BorrowCtrl'
      }).     //HIER ALLES NEU AUFSETZEN     
      when('/sysconf', {           
        templateUrl: 'html/systemconf_menu.html',
        controller: 'DetailCtrl'
      }).
      when('/adminconf', {
        templateUrl: 'html/adminconf_menu.html',
        controller: 'DetailCtrl'
      }).
      when('/list_borrow', {
        templateUrl: 'html/dashboard.html', //NOCH NICHT ERSTELLT
        controller: 'DetailCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

