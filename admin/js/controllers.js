'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination']);	

//==============================
//Request Admin list
//Description: The main-site controller, loads the list and controls the pagination, aswell the route to the detailview of an item
//Used in: list.html
//==============================
invControllers.controller('ListCtrl', function ($scope, $location, REST) {
  //Get all item informations from the server
  $scope.listData = REST.query();

  /*REST.typload(function(data){          //typeaheadlist request via rest-factory
  $scope.typeaheadData = data;            //NOT INCLUDED, WIP
  });*/
  
  var d_pageSize = 10;                    //default pageSize limit
  $scope.pageSize = d_pageSize;			      //Item limit per page

  $scope.sort = function(keyname){	      //sort option on click, call by reference
    $scope.sortKey = keyname;         //set the sortKey to the param passed
    $scope.reverse = !$scope.reverse; //if true make it false and vice versa
  }

  //Creates a new Item without an copy
  $scope.createNewItem = function(typ){      
    $scope.clearItem(); //clears the rent-cart
    
    //Link us to the right form
    if(typ == "Device")
    {      $location.path('/create_device');     }
    else
    {      $location.path('/create_material');   }         
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

//==============================
//Request Detail informations from specific item
//Used in: detail.html 
//==============================
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});
  //Gets all history informations of a specific item by id
  $scope.historyData = REST.historyLoad({ListItemId: 'item/getHistory/' + $routeParams.ListItemId});


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

  //Link to edit item form
  $scope.editItem = function(listID) {  
    $location.path('/edit_item/'  + listID); //link us to the edit form
  };

  //Link to copy item form
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

//==============================
//Create Item controller
//Used: create_material.html, create_device.html 
//==============================
invControllers.controller('CreateCtrl', ['$scope', '$routeParams', '$location', '$http', function($scope, $routeParams, $location, $http) {
  
//This js object will be send to the server for creating an item
$scope.createItem = {};

//Send creation to the server
$scope.createItemToServer = function(typ) {    
  $scope.transform(typ); //transform variables for the server
  
  if(typ == "Device") //Create Device
  { 
    //POST device to the server
    $http.post("/api/v1/restricted/device/create", createItem).success(function(data, status) {
      //SUCCESSFULL
      $scope.clearItem(); //clears the selected item
      $location.path('/list');
    });  
  }
  else  //Create Material
  {
    //POST material to the server
    $http.post("/api/v1/restricted/material/create", createItem).success(function(data, status) {
      //SUCCESSFULL
      $scope.clearItem(); //clears the selected item
      $location.path('/list');
    });
  }
};

//the server needs other variable names, so adjust them
$scope.transform = function(typ) {
  if($scope.selectedItems[0] != null){
    $scope.createItem.name = $scope.selectedItems[0].Name;
    $scope.createItem.state = $scope.selectedItems[0].State;
    $scope.createItem.saleprice = $scope.selectedItems[0].Saleprice;
    //$scope.createItem.createdbyid = $scope.selectedItems[0].CreatedbyId;
    $scope.createItem.comment = $scope.selectedItems[0].Comment;
    $scope.createItem.place = $scope.selectedItems[0].Place;
    $scope.createItem.category = $scope.selectedItems[0].Category;
    $scope.createItem.description = $scope.selectedItems[0].Description;
    $scope.createItem.visible = $scope.selectedItems[0].PublicVisible;
    if(typ == "Material") //extra material informations
    { 
      $scope.createItem.buildtype = $scope.selectedItems[0].Buildtype;
      $scope.createItem.uom = $scope.selectedItems[0].UoM;
      $scope.createItem.uom_short = $scope.selectedItems[0].UoM_short;
      $scope.createItem.storagevalue = $scope.selectedItems[0].StorageValue;
      $scope.createItem.criticalstoragevalue = $scope.selectedItems[0].CriticalStorageValue;
    } 
  }
}



  
}]);

//==============================
//ItemEdit controller
//Used: edit_item.html
//==============================
invControllers.controller('ItemEditCtrl', ['$scope', '$routeParams', '$location', '$http', 'REST', function($scope, $routeParams, $location, $http, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});

  //This js object will be send to the server for editing an item  
  $scope.updateItem = {};

  //Update/Edit item to the server
  $scope.saveEdit = function() {
    $scope.transform();     //transform variables for the server

    if($scope.detailData[0].material_id == 1) //Update Device
    {  
      //create url with the selected item
      var url = '/api/v1/restricted/device/update/' + $scope.updateItem.id;
      //POST device to the server
      $http.post(url, updateItem).success(function(data, status) {
        //SUCCESSFULL
        $scope.clearItem(); //clears the selected item
        $location.path('/listData/' + updateItem.Id);
      }); 
    }
    else  //Update Material
    { 
      //create url with the selected item
      var url = '/api/v1/restricted/material/update/' + $scope.updateItem.id;
      //POST material to the server 
      $http.post(url, updateItem).success(function(data, status) {
        //SUCCESSFULL
        $scope.clearItem(); //clears the selected item
        $location.path('/listData/' + updateItem.Id);
      });
    }
  };

  //the server needs other variable names, so adjust them
  $scope.transform = function() {
    $scope.updateItem.id = $scope.detailData[0].Id;
    $scope.updateItem.material_id = $scope.detailData[0].material_id;
    $scope.updateItem.name = $scope.detailData[0].Name;
    $scope.updateItem.state = $scope.detailData[0].State;
    $scope.updateItem.saleprice = $scope.detailData[0].Saleprice;
    //$scope.updateItem.createdbyid = $scope.detailData[0].CreatedbyId;
    $scope.updateItem.comment = $scope.detailData[0].Comment;
    $scope.updateItem.place = $scope.detailData[0].Place;
    $scope.updateItem.category = $scope.detailData[0].Category;
    $scope.updateItem.description = $scope.detailData[0].Description;
    $scope.updateItem.visible = $scope.detailData[0].PublicVisible;
    if($scope.detailData[0].material_id != 1) //extra material informations
    { 
      $scope.updateItem.buildtype = $scope.detailData[0].Buildtype;
      $scope.updateItem.uom = $scope.detailData[0].UoM;
      $scope.updateItem.uom_short = $scope.detailData[0].UoM_short;
      $scope.updateItem.storagevalue = $scope.detailData[0].StorageValue;
      $scope.updateItem.criticalstoragevalue = $scope.detailData[0].CriticalStorageValue; 
    }
  };
    
  $scope.viewDetail = function(listID) {    //change to detailview view
        $location.path('/listData/' + listID); 
  };

}]);


//==============================
//Rental controller
//Used: rental.html, rentallist.html 
//==============================
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

//==============================
//Main-controller
//Used: overall other controller 
//==============================
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