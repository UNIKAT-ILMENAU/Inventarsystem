
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
  function($routeProvider) {  
    $routeProvider.
      when('/', {                     
        templateUrl: 'html/login.html',    
        controller: 'loginCtrl'
      }).
      when('/login', {                            //Login
        templateUrl: 'html/login.html',
        controller: 'loginCtrl'
      }).
      when('/dashboard', {                        //Dashboard - Mainmenu
        templateUrl: 'html/dashboard.html',
        controller: 'MenuCtrl'
      }).     
      when('/sysconf', {                          //Sidemenu - Systemconfiguration
        templateUrl: 'html/systemconf_menu.html',
        controller: 'MenuCtrl'
      }).
      when('/adminconf', {                        //Sidemenu - Adminconfiguration
        templateUrl: 'html/adminconf_menu.html',
        controller: 'MenuCtrl'
      }).
      when('/list', {                             //Itemlist
        templateUrl: 'html/itemlist.html',    
        controller: 'ListCtrl'
      }).
      when('/listData/:ListItemId', {             //Item detail view
        templateUrl: 'html/detail.html',
        controller: 'DetailCtrl'
      }).
      when('/create_device', {                    //Create device
        templateUrl: 'html/create_device.html', 
        controller: 'CreateCtrl'
      }).
      when('/create_material', {                  //Create material
        templateUrl: 'html/create_material.html', 
        controller: 'CreateCtrl'
      }).
      when('/edit_item/:ListItemId', {            //Edit item view
        templateUrl: 'html/edit_item.html',
        controller: 'ItemEditCtrl'
      }).
      when('/rental', {                           //Rental form
        templateUrl: 'html/rental.html',    
        controller: 'RentalCtrl'
      }).
      when('/rentallist', {                       //All rented items list
        templateUrl: 'html/rentallist.html',      
        controller: 'RentalListCtrl'
      }).   
      when('/rentalData/:ListItemId', {           //Rented item detail view
        templateUrl: 'html/rental_detail.html',      
        controller: 'RentalDetailCtrl'
      }).
      when('/category', {                         //Category configuration  ###### CTRL FIX
        templateUrl: 'html/category.html',      
        controller: 'MenuCtrl'
      }).   
      when('/place', {                            //Place configuration      ###### CTRL FIX
        templateUrl: 'html/place.html',      
        controller: 'MenuCtrl'
      }).                                       
      when('/inviteAdmin', {                      //invite Admin form                
        templateUrl: 'html/inviteAdmin.html',
        controller: 'inviteAdminCtrl'
      }).
      when('/createNewAdmin', {                   //create a new admin after inviting
        templateUrl: 'html/createNewAdmin.html',
        controller: 'createNewAdminCtrl'
      }).
      when('/resetPassword', {                    //change password when already logged in
        templateUrl: 'html/resetPassword.html', 
        controller: 'resetPasswordAsAdminCtrl'
      }).
      when('/deleteAdmin', {                      //delete an admin
        templateUrl: 'html/deleteAdmin.html',
        controller: 'deleteAdminCtrl'
      }).
      when('/forgotPassword', {                   //change password when not logged in (forgot password)
        templateUrl: 'html/forgotPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      when('/newPassword', {                      //change password when not logged in
        templateUrl: 'html/newPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

