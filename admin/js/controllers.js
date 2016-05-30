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

  $scope.loadBorrow = function(){      
        $location.path('/borrow');       
    }

	$scope.viewDetail = function(listID) { 		//tr clickable, change to detailview view, activated via 1click
        $location.path('/listData/' + listID); 
    };

    $scope.resetFilter = function(){
      $scope.search = "";   //resets the filter options
      $scope.pageSize = d_pageSize; //resets the items per page size to default
    };

});

/*REQUEST DETAIL*/
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  $scope.detailData = REST.detailLoad({ListItemId: $routeParams.ListItemId});	//specific get of list item
  /* $scope.detailData = REST.get({ListItemId: $routeParams.ListItemId}); works aswell*/

  $scope.alert = [];
  $scope.addAlert = function(info) {
    if(info == 'error'){
    $scope.alert.push({msg: 'Error please try again!'});
    }else{
      $scope.alert.push({msg: 'Action successful'});
    }
  };

  $scope.borrowAdd = function(data) {
    $scope.addAlert($scope.addItem(data));

  };


  /*For the copy item*/
  $scope.copyItem = function(data, info) {  

    $scope.clearItem();
    $scope.addItem(data);
    /*Hier verlinkung zu der seite wo die items erstellt werden, davor aber erkennung*/
    if(info == 'Device'){
      $location.path('/create_device');
      //Daten müssen noch überarbeitet werden, sowie beim zurückgehen vom Kopieren löschen des ausgewählten objekts
      //$scope.alert.push({msg: 'Device successful'}); /*Verlinkung ohne msg am ende*/
    }else{
      $location.path('/create_material');
      //$scope.alert.push({msg: 'Material successful'}); /*Verlinkung ohne msg am ende*/
    }   
  };

}]);


/*Borrow Controller*/
invControllers.controller('BorrowCtrl', ['$scope', '$routeParams', 'REST', function($scope, $routeParams, REST) {

  

  $scope.borrow = 
   {
    'customer': {     //will be empty at the end... this is just for testing
        'firstname': 'Mark',
        'lastname': 'Marvel',
        'matrikel': '56433',
        'city': 'Ilmenau',
        'street': 'Ilmstreet 7',
        'zip': '0000093',
        'phone': '015263729',
        'email': 'test@test.de'
    },
    'items': []
   };

   $scope.borrow.items = $scope.selectedItems;

   $scope.removeItem2 = function(index) {  

    //$scope.selectedItems.splice(index, 1);
  };


}]);

/*main-controller over all other controller*/
invControllers.controller('indexCtrl', function ($scope, $location, $anchorScroll) {

  $scope.selectedItems = [];

/* For the list / borrow cart*/
  $scope.addItem = function(data) {  

    for (var i = 0; i < $scope.selectedItems.length; ++i) {    
      if ($scope.selectedItems[i].id === data.id) {
        return "error";
      }  
    }
    
    $scope.selectedItems.push(data);
    return "success";
  };

  $scope.removeItem = function(index) {  

    $scope.selectedItems.splice(index, 1);
  };

  $scope.clearItem = function() {  

    $scope.selectedItems = [];
  };


 
/* AUTOSTART EVENT
  $scope.$on('$viewContentLoaded', function(){
    
  });
*/




  $scope.scrollTo = function() {
      // set the location.hash to null/top
      $location.hash();			

      // call $anchorScroll() to use the scroll
      $anchorScroll();
    };
});

