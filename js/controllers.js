'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination']);	//include dirPagination for dirPagination functions

/*REQUEST LIST
Description: The mainsite controller, loads the list and controls the pagination, aswell the route to the detailview of an item
Used in: list.html
*/
invControllers.controller('ListCtrl', function ($scope, $location, REST) {
   REST.query(function(data){		//list request via rest-factory
	$scope.listData = data;
});

  $scope.pageSize = 10;				//default Item limit per page

  $scope.sort = function(keyname){	//sort option on click, call by reference
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    }


	$scope.viewDetail = function(listID) { 		//tr clickable, change to detailview view, activated via double click
        $location.path('/listData/' + listID); 
    };

});

/*REQUEST DETAIL*/
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', 'REST', function($scope, $routeParams, REST) {
  $scope.detailData = REST.detailLoad({ListItemId: $routeParams.ListItemId});	//specific get of list item
  /* $scope.detailData = REST.get({ListItemId: $routeParams.ListItemId}); works aswell*/
}]);

/*DUMMY CONTROLLER FOR TESTING NESTING*/
invControllers.controller('indexCtrl', function ($scope, REST, $location, $anchorScroll) {

  $scope.scrollTo = function(id) {
      // set the location.hash to the id of
      // the element you wish to scroll to.
      $location.hash();			//id workInProgress

      // call $anchorScroll() to use the scroll
      $anchorScroll();
    };
});

