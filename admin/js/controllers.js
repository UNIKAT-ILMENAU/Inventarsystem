'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination']);	//include dirPagination for dirPagination functions

/* ADMIN INVENTORY LIST CONTROLLER
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

/* ITEM DETAIL CONTROLLER */
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

  /* Edit item function*/
  $scope.editItem = function(listID) {  
    $location.path('/edit_item/'  + listID); //link us to the edit form
  };

  /* Copy item function */
  $scope.copyItem = function(data, info) {  

    $scope.clearItem();   //clear the borrow cart
    $scope.addItem(data); //adds the item that we can use it
    /*Link to the right create form*/
    if(info == 'Device'){
      $location.path('/create_device');
    }else if(info == 'Material'){
      $location.path('/create_material');
    }   
  };

}]);

/* ITEM EDIT CONTROLLER */
invControllers.controller('ItemEditCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  $scope.detailData = REST.detailLoad({ListItemId: $routeParams.ListItemId}); //specific get of list item

  $scope.viewDetail = function(listID) {    //change to detailview view
        $location.path('/listData/' + listID); 
  };

  $scope.saveEdit = function(data) {   
        //save to server
        //get message if successful
        //redirect to DetailView

        //just for the testing
        $location.path('/listData/' + data.Id);
  };

}]);


/* BORROW CONTROLLER */
invControllers.controller('BorrowCtrl', ['$scope', '$routeParams', 'REST', function($scope, $routeParams, REST) {

  //This is our borrow object with all informations about the current-borrow_cart
  $scope.borrow = 
   {
    'customer': {     
        'firstname': '',
        'lastname': '',
        'matrikel': '',
        'city': '',
        'street': '',
        'zip': '',
        'phone': '',
        'email': '',
        'date': '',
    },
    'items': []
   };

   //give all selected items the borrow object
   $scope.borrow.items = $scope.selectedItems;  

  $scope.sendBorrow = function(){
    //needs to be like this cause datepicker doesnt work with ng-change
    $scope.borrow.customer.date = document.getElementById("borrowDate").value;
  };

  //Datepicker   
  $('*[id=borrowDate]').appendDtpicker({ 
    "dateOnly": true,
    "dateFormat": "YYYY-MM-DD",
    "futureOnly": true
  });
          
}]);

/* main-controller over all other controller */
invControllers.controller('indexCtrl', function ($scope, $location, $anchorScroll) {

  $scope.selectedItems = [];

/* For the list / borrow cart*/
  $scope.addItem = function(data) {  

    for (var i = 0; i < $scope.selectedItems.length; ++i) {    
      if ($scope.selectedItems[i].Id === data.Id) {
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


//loginController: sends login-data, gets and stores token, throws error if invalid userdata, routes to next page
invControllers.controller('loginCtrl', loginCtrl);
function loginCtrl($scope, $window, $location, $http){

  $scope.signIn = function(){
    var userData = {
        username: $scope.username,
        password: $scope.password
    };  
    $http({
      method: 'Post',
      //(!) URL
      url: '',
      data: userData.username + ";" + userData.password //oder JSON.parse(userdata)
    })
    .then(
      function(response){
        //store token
        $window.localStorage.token = response.token;

        //(!)route
        //$location.path('/dashboard');
      },  
        //if no response throw error-msg
      function(err) {
        $scope.error = {
        show: true,
        message: err.data
        }
      }
    );
  }
}