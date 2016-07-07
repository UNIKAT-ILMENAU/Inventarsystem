
'use strict';

/* App Module */

var invApp = angular.module('invApp', [
  'ngRoute', 'invControllers', 'invServices', 'invFilter'
]);

//injecting JWT Authentication in http-Header by using factory.  
invApp.config(['$httpProvider', function($httpProvider){
  $httpProvider.interceptors.push('tokenInterceptor');
}]);


//Route definition
invApp.config(['$routeProvider',  
  function($routeProvider) {  
    $routeProvider.
      when('/', {                     
        templateUrl: 'html/itemlist/edit_item.html',    
        controller: 'ItemEditCtrl'
      }).
      when('/login', {                            //Login
        templateUrl: 'html/login/login.html',
        controller: 'loginCtrl'
      }).
      when('/dashboard', {                        //Dashboard - Mainmenu
        templateUrl: 'html/dashboard.html',
        controller: 'MenuCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).     
      when('/sysconf', {                          //Sidemenu - Systemconfiguration
        templateUrl: 'html/systemconf_menu.html',
        controller: 'MenuCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/adminconf', {                        //Sidemenu - Adminconfiguration
        templateUrl: 'html/adminconf_menu.html',
        controller: 'MenuCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/list', {                             //Itemlist
        templateUrl: 'html/itemlist/itemlist.html',    
        controller: 'ListCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/listData/:ListItemId', {             //Item detail view
        templateUrl: 'html/itemlist/detail.html',
        controller: 'DetailCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/create_device', {                    //Create device
        templateUrl: 'html/itemlist/create_device.html', 
        controller: 'CreateCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/create_material', {                  //Create material
        templateUrl: 'html/itemlist/create_material.html', 
        controller: 'CreateCtrl',
        resolve: {function(authCheck){authCheck.check()}} 
      }).
      when('/edit_item/:ListItemId', {            //Edit item view
        templateUrl: 'html/itemlist/edit_item.html',
        controller: 'ItemEditCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/rental', {                           //Rental form
        templateUrl: 'html/itemlist/rental.html',    
        controller: 'RentalCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/rentallist', {                       //All rented items list
        templateUrl: 'html/rentallist/rentallist.html',      
        controller: 'RentalListCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).   
      when('/rentalData/:ListItemId', {           //Rented item detail view
        templateUrl: 'html/rentallist/rental_detail.html',      
        controller: 'RentalDetailCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/category', {                         //Category configuration
        templateUrl: 'html/systemconf/category.html',      
        controller: 'CategoryCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).   
      when('/place', {                            //Place configuration
        templateUrl: 'html/systemconf/place.html',      
        controller: 'PlaceCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).                                       
      when('/inviteAdmin', {                      //invite Admin form                
        templateUrl: 'html/adminconf/inviteAdmin.html',
        controller: 'inviteAdminCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/createNewAdmin/:token', {                   //create a new admin after inviting
        templateUrl: 'html/adminconf/createNewAdmin.html',
        controller: 'createNewAdminCtrl'
      }).
      when('/resetPassword', {                    //change password when already logged in
        templateUrl: 'html/adminconf/resetPassword.html', 
        controller: 'resetPasswordAsAdminCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/deleteAdmin', {                      //delete an admin
        templateUrl: 'html/adminconf/deleteAdmin.html',
        controller: 'deleteAdminCtrl',
        resolve: {function(authCheck){authCheck.check()}}
      }).
      when('/forgotPassword', {                   //change password when not logged in (forgot password)
        templateUrl: 'html/login/forgotPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      when('/newPassword/:tmpToken', {                      //change password when not logged in
        templateUrl: 'html/login/newPassword.html',
        controller: 'forgotPasswordCtrl'
      }).
      otherwise({
        redirectTo: '/'
      });
  }]);