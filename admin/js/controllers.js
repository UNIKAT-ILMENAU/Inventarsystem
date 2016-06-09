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

  //Creates a new Item without an copy
  $scope.createNewItem = function(typ){      
    $scope.clearItem();

    if(typ == "Device")
    {
      $location.path('/create_device'); 
    }
    else
    {
      $location.path('/create_material'); 
    }         
  }

  //Loads the rental form
  $scope.loadRental = function(){      
    $location.path('/rental');       
  }

  //Loads the detailView form
	$scope.viewDetail = function(listID) { 		//tr clickable, change to detailview view, activated via 1click
    $location.path('/listData/' + listID); 
  };

  //Resets all filter options in the list
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

  $scope.rentalAdd = function(data) {
    $scope.addAlert($scope.addItem(data));

  };

  /* Edit item function*/
  $scope.editItem = function(listID) {  
    $location.path('/edit_item/'  + listID); //link us to the edit form
  };

  /* Copy item function */
  $scope.copyItem = function(data, info) {  

    $scope.clearItem();   //clear the rental cart
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
invControllers.controller('CreateCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  
//This js object will be send to the server for creating an item
$scope.createItem = {};

$scope.transform = function() {
  if($scope.selectedItems[0] != null){
    $scope.createItem.name = $scope.selectedItems[0].Name;
    $scope.createItem.state = $scope.selectedItems[0].State;
    $scope.createItem.cost = $scope.selectedItems[0].Cost;
    $scope.createItem.saleprice = $scope.selectedItems[0].Saleprice;
    //$scope.createItem.createdbyid = $scope.selectedItems[0].CreatedbyId;
    $scope.createItem.place = $scope.selectedItems[0].Place;
    $scope.createItem.category = $scope.selectedItems[0].Category;
    $scope.createItem.description = $scope.selectedItems[0].Description;
    $scope.createItem.visible = $scope.selectedItems[0].PublicVisible;
    $scope.createItem.buildtype = $scope.selectedItems[0].Buildtype;
    $scope.createItem.uom = $scope.selectedItems[0].UoM;
    $scope.createItem.uom_short = $scope.selectedItems[0].UoM_short;
    $scope.createItem.storagevalue = $scope.selectedItems[0].StorageValue;
    $scope.createItem.criticalstoragevalue = $scope.selectedItems[0].CriticalStorageValue; 
  }
}

//LoadsEverything to the server
$scope.createItemToServer = function() {    
    $scope.transform(); 
    //save to server
    //get message if successful
    //redirect to DetailView  
};
  
}]);

/* ITEM EDIT CONTROLLER */
invControllers.controller('ItemEditCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  $scope.detailData = REST.detailLoad({ListItemId: $routeParams.ListItemId}); //specific get of list item

  //This js object will be send to the server for creating an item
  $scope.updateItem = {};

    $scope.transform = function() {
      $scope.updateItem.id = $scope.detailData.Id;
      $scope.updateItem.material_id = $scope.detailData.material_id;
      $scope.updateItem.name = $scope.detailData.Name;
      $scope.updateItem.state = $scope.detailData.State;
      $scope.updateItem.cost = $scope.detailData.Cost;
      $scope.updateItem.saleprice = $scope.detailData.Saleprice;
      //$scope.updateItem.createdbyid = $scope.detailData.CreatedbyId;
      $scope.updateItem.place = $scope.detailData.Place;
      $scope.updateItem.category = $scope.detailData.Category;
      $scope.updateItem.description = $scope.detailData.Description;
      $scope.updateItem.visible = $scope.detailData.PublicVisible;
      $scope.updateItem.buildtype = $scope.detailData.Buildtype;
      $scope.updateItem.uom = $scope.detailData.UoM;
      $scope.updateItem.uom_short = $scope.detailData.UoM_short;
      $scope.updateItem.storagevalue = $scope.detailData.StorageValue;
      $scope.updateItem.criticalstoragevalue = $scope.detailData.CriticalStorageValue; 
    };
    
  $scope.viewDetail = function(listID) {    //change to detailview view
        $location.path('/listData/' + listID); 
  };

  $scope.saveEdit = function(data) {
        $scope.transform();   
        //save to server
        //get message if successful
        //redirect to DetailView

        //just for the testing
        //$location.path('/listData/' + data.Id);
  };

}]);


/* Rental CONTROLLER */
invControllers.controller('RentalCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {

  //redirect us when we have accidentally are on the rental page
  if($scope.selectedItems[0] == null)
  {
      $location.path('/list');
  }

  //This is our rental object with all informations about the current-rental_cart
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
   $scope.borrow.items = $scope.selectedItems;  /* Here only the ID Amount of the item */

  $scope.sendRental = function(){
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

  //localwebstorage for the selected items
  $scope.selectedItems = [];

/* For the list / rental cart*/
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
    //redirect us when we dont have items in rentallist and are not in the listview
    if($scope.selectedItems[0] == null && $location.url() != '/list')
    {
        $location.path('/list');
    }
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