
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
        templateUrl: 'html/login/login.html',    
        controller: 'loginCtrl'
      }).
      when('/login', {                            //Login
        templateUrl: 'html/login/login.html',
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
        templateUrl: 'html/itemlist/itemlist.html',    
        controller: 'ListCtrl'
      }).
      when('/listData/:ListItemId', {             //Item detail view
        templateUrl: 'html/itemlist/detail.html',
        controller: 'DetailCtrl'
      }).
      when('/create_device', {                    //Create device
        templateUrl: 'html/itemlist/create_device.html', 
        controller: 'CreateCtrl'
      }).
      when('/create_material', {                  //Create material
        templateUrl: 'html/itemlist/create_material.html', 
        controller: 'CreateCtrl'
      }).
      when('/edit_item/:ListItemId', {            //Edit item view
        templateUrl: 'html/itemlist/edit_item.html',
        controller: 'ItemEditCtrl'
      }).
      when('/rental', {                           //Rental form
        templateUrl: 'html/itemlist/rental.html',    
        controller: 'RentalCtrl'
      }).
      when('/rentallist', {                       //All rented items list
        templateUrl: 'html/rentallist/rentallist.html',      
        controller: 'RentalListCtrl'
      }).   
      when('/rentalData/:ListItemId', {           //Rented item detail view
        templateUrl: 'html/rentallist/rental_detail.html',      
        controller: 'RentalDetailCtrl'
      }).
      when('/category', {                         //Category configuration
        templateUrl: 'html/systemconf/category.html',      
        controller: 'CategoryCtrl'
      }).   
      when('/place', {                            //Place configuration
        templateUrl: 'html/systemconf/place.html',      
        controller: 'PlaceCtrl'
      }).                                       
      when('/inviteAdmin', {                      //invite Admin form                
        templateUrl: 'html/adminconf/inviteAdmin.html',
        controller: 'inviteAdminCtrl'
      }).
      when('/createNewAdmin', {                   //create a new admin after inviting
        templateUrl: 'html/adminconf/createNewAdmin.html',
        controller: 'createNewAdminCtrl'
      }).
      when('/resetPassword', {                    //change password when already logged in
        templateUrl: 'html/adminconf/resetPassword.html', 
        controller: 'resetPasswordAsAdminCtrl'
      }).
      when('/deleteAdmin', {                      //delete an admin
        templateUrl: 'html/adminconf/deleteAdmin.html',
        controller: 'deleteAdminCtrl'
      }).
      when('/forgotPassword', {                   //change password when not logged in (forgot password)
        templateUrl: 'html/login/forgotPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      when('/newPassword', {                      //change password when not logged in
        templateUrl: 'html/login/newPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);

