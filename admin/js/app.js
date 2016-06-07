
'use strict';

/* App Module */

var invApp = angular.module('invApp', [
  'ngRoute', 'invControllers', 'invServices', 'invFilter'
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
      when('/create_device', {                    //controller needs to get selected
        templateUrl: 'html/create_device.html', 
        controller: 'DetailCtrl'
      }).
      when('/create_material', {                  //controller needs to get selected
        templateUrl: 'html/create_material.html', 
        controller: 'DetailCtrl'
      }).
      when('/listData/:ListItemId', {  //ItemDetailView
        templateUrl: 'html/detail.html',
        controller: 'DetailCtrl'
      }).
      when('/edit_item/:ListItemId', {  //EditItemView
        templateUrl: 'html/edit_item.html',
        controller: 'ItemEditCtrl'
      }).
      when('/borrow', {                   //BorrowForm
        templateUrl: 'html/borrow.html',    
        controller: 'BorrowCtrl'
      }).
      when('/borrowlist', {               //AllBorrowedItemsList
        templateUrl: 'html/borrowlist.html',    
        controller: 'BorrowCtrl'
      }).        
      when('/sysconf', {                          //controller needs to get selected
        templateUrl: 'html/systemconf_menu.html',
        controller: 'DetailCtrl'
      }).
      when('/adminconf', {                        //controller needs to get selected
        templateUrl: 'html/adminconf_menu.html',
        controller: 'DetailCtrl'
      }).
      when('/list_borrow', {                      //controller needs to get selected
        templateUrl: 'html/dashboard.html', 
        controller: 'DetailCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

