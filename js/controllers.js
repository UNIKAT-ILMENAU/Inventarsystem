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

  REST.typload(function(data){   //typeaheadlist request via rest-factory
  $scope.typeaheadData = data;
  });
  
   var d_pageSize = 10;               //default pageSize limit
  $scope.pageSize = d_pageSize;				//Item limit per page

  $scope.sort = function(keyname){	//sort option on click, call by reference
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    }


	$scope.viewDetail = function(listID) { 		//tr clickable, change to detailview view, activated via 1click
        $location.path('/listData/' + listID); 
    };

    $scope.resetFilter = function(){
      $scope.search = "";   //resets the filter options
      $scope.pageSize = d_pageSize; //resets the items per page size to default
    };

});

/*REQUEST DETAIL FROM SELECTED ITEM*/
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', 'REST', function($scope, $routeParams, REST) {
  $scope.detailData = REST.detailLoad({ListItemId: $routeParams.ListItemId});	//specific get of list item
  /* $scope.detailData = REST.get({ListItemId: $routeParams.ListItemId}); works aswell*/
}]);

/*MAIN-CONTROLLER OVERALL OTHER CONTROLLER*/
invControllers.controller('indexCtrl', function ($scope, REST, $location, $anchorScroll) {

  $scope.scrollTo = function() {
      // set the location.hash to null/top
      $location.hash();			

      // call $anchorScroll() to use the scroll
      $anchorScroll();
    };
});

