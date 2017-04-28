'use strict';

/* App Module */

var invApp = angular.module('invApp', ['ngRoute', 'invControllers', 'invServices']);

//==============================
//Route definition
//==============================
invApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.when('/', {
            templateUrl: 'html/list.html',    //public list route
            controller: 'ListCtrl'
        }).when('/listData/:ListItemId', {
            templateUrl: 'html/detail.html',  //public detailview route
            controller: 'DetailCtrl'
        }).otherwise({
            redirectTo: '/'
        });
    }]);

invApp.constant('customData', {
    'org_name': 'Example Org',
    'org_address': 'Some Street 1 <br> City 49 <br> More Data',
    'org_main_color': '#008080',
    'org_email': 'hello@example.com',
    'org_facebook': 'https://www.facebook.com/example'
});