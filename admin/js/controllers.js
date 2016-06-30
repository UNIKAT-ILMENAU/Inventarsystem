'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination', 'ngStorage']); 

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
  $scope.pageSize = d_pageSize;           //Item limit per page

  $scope.sort = function(keyname){    //sort option on click, call by reference
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
  $scope.viewDetail = function(listID) {    //tr clickable, change to detailview view, activated via 1click
    $location.path('/listData/' + listID); 
  };

  //Resets all filter options in the list
  $scope.resetFilter = function(){
    $scope.search = "";   //resets the filter options
    $scope.pageSize = d_pageSize; //resets the items per page size to default
  };

});

//==============================
//Menu controller handles menu functions
//Used in: dashboard.html - adminconf_menu.html - systemconf_menu.html 
//==============================
invControllers.controller('MenuCtrl', function () {
}); 

//==============================
//Request Detail informations from specific item
//Used in: detail.html 
//==============================
invControllers.controller('DetailCtrl', ['$scope','$localStorage', '$routeParams', '$location', '$http', 'REST', function($scope, $routeParams, $location, $http, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});
  //Gets the place as a string
  $scope.Place_name = REST.detailPlaceLoad({ListItemId: 'place/search/' + $routeParams.ListItemId});
  //Gets all history informations of a specific item by id
  $scope.historyData = REST.historyLoad({ListItemId: 'item/getHistory/' + $routeParams.ListItemId});

  //Reloads all the data of a specific item by id (reloads detailView data)
  $scope.ReloadDatas = function() {  
    //Gets all informations of a specific item by id
    $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});
    //Gets all history informations of a specific item by id
    $scope.historyData = REST.historyLoad({ListItemId: 'item/getHistory/' + $routeParams.ListItemId});
  };

  //==============================
  //Simple Alert System (Work in progress)
  //==============================
  $scope.alert = [];
  $scope.addAlert = function(info) {
    if(info == 'error'){
    $scope.alert.push({msg: 'Error please try again!'});
    }else{
      $scope.alert.push({msg: 'Action successful'});
    }
  };

  //==============================
  //EVENTS (DetailView)
  //==============================
  $scope.rentalAdd = function(data) {
    $scope.addAlert($scope.addItem(data));
  };

  //Link to edit item form
  $scope.editItem = function(listID) {  
    //link us to the edit form of the selected item by id
    $location.path('/edit_item/'  + listID); 
  };

  //Link to copy item form
  $scope.copyItem = function(data, info) {  

    $scope.clearItem();   //clear the rental cart
    $scope.addItem(data); //adds the item that we can use it

    //Link to the right create form
    if(info == 'Device'){       $location.path('/create_device');    }
    else if(info == 'Material'){$location.path('/create_material');  }   
  };

  //==============================
  //device defect / missing state update
  //==============================
  $scope.deviceEvent = function(info, stateID) { 
    //sets the title in the devicemodal 
    $scope.title = info;
    $scope.state = stateID;
  };

  //modal function for the device state update (defect/missing)
  $scope.updateStateEvent = function(itemID, stateID, comment) { 
    var Indata = {'itemid': itemID, 'comment': comment, 'createdbyid': $localStorage.user_id }; //NEEDS TO BE IMPLEMENTED
    if(stateID == 2){
      //POST state device to the server
      $http.post("/api/v1/restricted/event/8", Indata).success(function(data, status) {
        //SUCCESSFULL //alert("success");
        alert("success");
        $scope.ReloadDatas();  
      });
    } else if(stateID == 3)
    {
      //POST state device to the server
      $http.post("/api/v1/restricted/event/9", Indata).success(function(data, status) {
        //SUCCESSFULL //alert("success");
        $scope.ReloadDatas();
      });
    }
  };

  //==============================
  //material used / stockup function
  //==============================
  $scope.materialEvent = function(info) { 
    //sets the title in the materialmodal 
    $scope.title = info;
  };

  //modal function for the material used / stockup function
  $scope.updateMaterialEvent = function(title, amount, itemID, createdbyid, price) {  
    //check event and if we have a positiv amount
    if(title == "used" && amount > 0 )
    {
      var Indata = {'amount': amount, 'itemid': itemID, 'createdbyid': createdbyid }; //NEEDS TO BE IMPLEMENTED
      //POST used material to the server
      $http.post("/api/v1/restricted/event/6", Indata).success(function(data, status) {
        //SUCCESSFULL alert("success" + $scope.detailData[0].Id);
        $scope.ReloadDatas();
      });
    } //check event and if we have a positiv amount
    else if(title == "stock up" && amount > 0 )
    {    
      var Indata = {'amount': amount, 'itemid': itemID, 'createdbyid': createdbyid };
      //POST stock up material to the server
      $http.post("/api/v1/restricted/event/7", Indata).success(function(data, status) {
        //SUCCESSFULL alert("success" + $scope.detailData[0].Id);
        $scope.ReloadDatas();
      });
    }else if(title == "sell" && amount > 0)
    {   
    alert(price); 
      var Indata = {'amount': amount, 'itemid': itemID, 'createdbyid': createdbyid, 'price': price };
      //POST sell material to the server
      $http.post("/api/v1/restricted/event/10", Indata).success(function(data, status) {
        //SUCCESSFULL alert("success" + $scope.detailData[0].Id);
        $scope.ReloadDatas();
      });
    }
  };

}]);

//==============================
//Create Item controller
//Used: create_material.html, create_device.html 
//==============================
invControllers.controller('CreateCtrl', ['$scope','$localStorage', '$routeParams', '$location', '$http', function($scope, $localStorage,$routeParams, $location, $http) {

  //Send create to the server
  $scope.createItemToServer = function(typ) {    
    
    if(typ == "Device") //Create Device
    { 
     var Indata = { 'name': $scope.selectedItems[0].Name, 
                    'state': $scope.selectedItems[0].State,
                    'description': $scope.selectedItems[0].Description,
                    'category': $scope.selectedItems[0].Category, //NEEDS TO BE IMPLEMENTED
                    'visible': $scope.selectedItems[0].PublicVisible,
                    'place': $scope.selectedItems[0].Place,       //NEEDS TO BE IMPLEMENTED
                    'createdbyid': $localStorage.user_id,                             //NEEDS TO BE IMPLEMENTED
                    'comment': $scope.selectedItems[0].Comment
                };
      //POST device to the server
      $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
        //SUCCESSFULL alert("success");
        $scope.clearItem();       //clears the selected item
        $location.path('/list');  //redirect to the inventory list
      });  
    }
    else  //Create Material
    {
     var Indata = { 'name': $scope.selectedItems[0].Name, 
                    'state': $scope.selectedItems[0].State,
                    'category': $scope.selectedItems[0].Category, //NEEDS TO BE IMPLEMENTED
                    'description': $scope.selectedItems[0].Description,
                    'visible': $scope.selectedItems[0].PublicVisible,
                    'saleprice': $scope.selectedItems[0].SalePrice,
                    'place': $scope.selectedItems[0].Place,       //NEEDS TO BE IMPLEMENTED
                    'createdbyid': $localStorage.user_id,                             //NEEDS TO BE IMPLEMENTED
                    'buildtype': $scope.selectedItems[0].Buildtype,
                    'uom': $scope.selectedItems[0].UoM,
                    'uom_short': $scope.selectedItems[0].UoM_short,
                    'storagevalue': $scope.selectedItems[0].StorageValue,
                    'criticalstoragevalue': $scope.selectedItems[0].CriticalStorageValue,
                    'comment': $scope.selectedItems[0].Comment
                    };

      //POST material to the server
      $http.post("/api/v1/restricted/material/create", Indata).success(function(data, status) {
        //SUCCESSFULL alert("success");
        $scope.clearItem();       //clears the selected item
        $location.path('/list');  //redirect to the inventory list
      });
    }
  };

  //=========================================
  //Options and default values for dropdowns
  //=========================================

  //options and default('available') for state in create_device
  $scope.deviceStates = [{ name: 'Not available', value: 0 },
                    { name: 'Available', value: 1 },
                    { name: 'Defective', value: 2 },
                    { name: 'Missing', value: 3 } 
  ];

  //options and default('available') for state in create_material
  $scope.materialStates = [{ name: 'Not available', value: 0 },
                           { name: 'Available', value: 1 } 
  ];

  //options and default('Visible') for PublicVisible in create_material/create_device
  $scope.Visibility = [{ name: 'Not visible', value: 0 },
                       { name: 'Visible', value: 1 } 
  ];

  //==============================
  //Get all places from server
  //==============================

  //var nesseary for GET places-array and nesting array 
  var allPlaces = [];
  var placeResult;
  var placeTree;

  //GET places-array
  $http({
    method: 'GET',
    url: '/api/v1/restricted/place/allPlace'
  }).then(function placeSuccess(response) {
      //handles success
      allPlaces = response.data;

      //call functions to format query for rendering in html-template as nested list
      placeResult = _queryTreeSort({q: allPlaces});
      placeTree = _makeTree({q: placeResult});

      //for rendering nested list --> input is placeTree
      $scope.nestedPlaces = placeTree;            
  }, function placeError(response) {
      //handles error
      alert("An error occured. Could not load all places.");
  }); 
  

  //gets input from newPlaceValue 
  $scope.places = {name: ""};

  //changes input of places when new radio-button is selected
  $scope.newPlaceValue = function(n) {
    $scope.places = {name: n};
  };

  //==============================
  //Get all categories from server
  //==============================

  //var nesseary for GET categories-array and nesting array 
  var allCategories = [];
  var categoryResult;
  var categoryTree;

  //GET categories-array
  $http({
    method: 'GET',
    url: '/api/v1/restricted/category/allCategory'
  }).then(function categorySuccess(response) {
      //handles success
      allCategories = response.data;

      //call functions to format query for rendering in html-template as nested list
      categoryResult = _queryTreeSort({q: allCategories});
      categoryTree = _makeTree({q: categoryResult});

      //for rendering nested list --> input is categoryTree
      $scope.nestedCategories = categoryTree;            
  }, function categoryError(response) {
      //handles error
      alert("An error occured. Could not load all categories.");
  });

  //gets input from newCategoryValue 
  $scope.categories = {name: ""};

  //changes input of categories when new radio-button is selected
  $scope.newCategoryValue = function(n) {
    $scope.categories = {name: n};
  };

}]);

//==============================
//ItemEdit controller
//Used: edit_item.html
//==============================
invControllers.controller('ItemEditCtrl', ['$scope','$localStorage', '$routeParams', '$location', '$http', 'REST', function($scope, $localStorage, $routeParams, $location, $http, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});

  //=========================================
  //Options and default values for dropdowns
  //=========================================
  //options and default('available') for state of device in edit_item.html
  $scope.deviceStates = [{ name: 'Not available', value: 0 },
                    { name: 'Available', value: 1 },
                    { name: 'Defective', value: 2 },
                    { name: 'Missing', value: 3 } 
  ];

  //options and default('available') for state of material in edit_item.html
  $scope.materialStates = [{ name: 'Not available', value: 0 },
                           { name: 'Available', value: 1 } 
  ];

  //options and default('Visible') for PublicVisible in edit_item.html
  $scope.Visibility = [{ name: 'Not visible', value: 0 },
                       { name: 'Visible', value: 1 } 
  ];

  //Update/Edit item to the server
  $scope.saveEdit = function() {
    if($scope.detailData[0].material_id == 1) //Update Device
    {   
      var Indata = {'name': $scope.detailData[0].Name, 
                    'state': $scope.detailData[0].State,
                    'description': $scope.detailData[0].Description,
                    'category': 3,            //NEEDS TO BE IMPLEMENTED
                    'visible': $scope.detailData[0].PublicVisible,
                    'place': 3,               //NEEDS TO BE IMPLEMENTED
                    'createdbyid': $localStorage.user_id,         //NEEDS TO BE IMPLEMENTED
                    'comment': $scope.detailData[0].Comment
                    };
      
      //create url with the selected item
      var url = "/api/v1/restricted/device/update/" + $scope.detailData[0].Id;
      //POST device to the server
      $http.post(url, Indata).success(function(data, status) {
        //SUCCESSFULL alert("success device update");
        $location.path('/listData/' + $scope.detailData[0].Id); //redirect to detailView
      });
    }
    else  //Update Material
    { 
      var Indata = {'name': $scope.detailData[0].Name, 
                    'state': $scope.detailData[0].State,
                    'category': 3, //NEEDS TO BE IMPLEMENTED
                    'description': $scope.detailData[0].Description,
                    'visible': $scope.detailData[0].PublicVisible,
                    'saleprice': $scope.detailData[0].SalePrice,
                    'place': 3, //NEEDS TO BE IMPLEMENTED
                    'createdbyid': $localStorage.user_id, //NEEDS TO BE IMPLEMENTED
                    'buildtype': $scope.detailData[0].Buildtype,
                    'uom': $scope.detailData[0].UoM,
                    'uom_short': $scope.detailData[0].UoM_short,
                    'criticalstoragevalue': $scope.detailData[0].CriticalStorageValue,
                    'comment': $scope.detailData[0].Comment
                    };

      //create url with the selected item
      var url = '/api/v1/restricted/material/update/' + $scope.detailData[0].Id;
      //POST material to the server 
      $http.post(url, Indata).success(function(data, status) {
        //SUCCESSFULL alert("success material update");
        $location.path('/listData/' + $scope.detailData[0].Id); //redirect to detailView
      });
    }
  };
    
  $scope.viewDetail = function(listID) {        //change to detailview view
        $location.path('/listData/' + listID);  //redirect to detailView
  };

}]);


//==============================
//Rental controller
//Used: rental.html 
//==============================
invControllers.controller('RentalCtrl', ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'REST', function($scope, $localStorage, $routeParams, $location, $http, REST) {

  //redirect us, when we are accidentally on the rental page
  if($scope.selectedItems[0] == null)
  {  $location.path('/list');  }

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
        'enddate': '',
        'createdbyid': '',
        'comment': ''
    } 
   };

  $scope.sendRental = function(){
    //needs to be like this cause datepicker doesnt work with ng-change
    $scope.borrow.customer.enddate = document.getElementById("enddate").value;

    var Indata = {'firstname': $scope.borrow.customer.firstname, 
                  'lastname': $scope.borrow.customer.lastname,
                  'city': $scope.borrow.customer.city, 
                  'street':$scope.borrow.customer.street,
                  'zip': $scope.borrow.customer.zip,
                  'matrikel': $scope.borrow.customer.matrikel,
                  'phone': $scope.borrow.customer.phone, 
                  'email': $scope.borrow.customer.email, 
                  'enddate': $scope.borrow.customer.enddate,
                  'createdbyid': $localStorage.user_id,             //NEEDS TO BE IMPLEMENTED
                  'comment': $scope.borrow.customer.comment,
                  'ids': [],
                  'amounts': []
                  };            
    
    for (var i = 0; i < $scope.selectedItems.length ; i++) {
      Indata.ids.push($scope.selectedItems[i].Id);
      Indata.amounts.push($scope.selectedItems[i].amount);
    }
    $scope.testvar = Indata;
    //POST rental to the server 
    $http.post("/api/v1/restricted/rental/create", Indata).success(function(data, status) {
      //SUCCESSFULL alert("success update");
      $scope.clearItem();
      $location.path('/list'); //redirect to inventory list
    });
  }

  $scope.sendRental2 = function(){
    //needs to be like this cause datepicker doesnt work with ng-change
    $scope.borrow.customer.date = document.getElementById("borrowDate").value;

    //POST device to the server
    /*$http.post("/api/v1/restricted/device/create", $scope.borrow).success(function(data, status) {
      //SUCCESSFULL
      $scope.clearItem(); //clears the selected item
      $location.path('/list');
    });*/


  };

  //Datepicker   
  $('*[id=enddate]').appendDtpicker({ 
    "dateOnly": true,
    "dateFormat": "YYYY-MM-DD",
    "futureOnly": true
  });
          
}]);

//==============================
//Rental list controller
//Used: rentallist.html
//==============================
invControllers.controller('RentalListCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  //Get all item informations from the server
  $scope.listData = REST.allOpenRental(); //need another api request here

  //Switch beetween AllOpenRentals/AlleRentals
  $scope.switchRentalList = function(listname){
    
    if(listname == "open")
    {
      //Get all item informations from the server
      $scope.listData = REST.allOpenRental(); 
    }else if(listname =="all"){
      //Get all item informations from the server
      $scope.listData = REST.allRental();
    }
     
  };

  var d_pageSize = 10;                    //default pageSize limit
  $scope.pageSize = d_pageSize;           //Item limit per page

  $scope.sort = function(keyname){    //sort option on click, call by reference
    $scope.sortKey = keyname;         //set the sortKey to the param passed
    $scope.reverse = !$scope.reverse; //if true make it false and vice versa
  }

  //Loads the detailView form
  $scope.viewDetail = function(rentalID) {    //tr clickable, change to detailview view, activated via 1click
    $location.path('/rentalData/' + rentalID); 
  };

  //Resets all filter options in the list
  $scope.resetFilter = function(){
    $scope.search = "";   //resets the filter options
    $scope.pageSize = d_pageSize; //resets the items per page size to default
  };


}]);

//==============================
//Rental detail controller
//Used: rental_detail.html
//==============================
invControllers.controller('RentalDetailCtrl', ['$scope', '$localStorage', '$routeParams', '$location','$http', 'REST', function($scope, $routeParams, $location, $http, REST) {
  //Gets all user informations of a specific rental by id
  $scope.detailData = REST.detailRentalUserLoad({ListItemId: 'rental/SingleRentals/' + $routeParams.ListItemId});
  //Gets all item informations of the loaded rental
  $scope.itemData = REST.detailRentalItemLoad({ListItemId: 'rental/SingleRentalsItems/' + $routeParams.ListItemId});


  //==============================
  //Item rented events
  //==============================
  //bugfixing modal bug
  $scope.amount_value;

  $scope.change_amount = function(value) { 
    $scope.amount_value = value;
  };
  //Lost event
  $scope.lostEvent = function(ItemID, value) { 
    //sets the title in the modal 
    $scope.title = "lost";
    $scope.amount = value;
    $scope.itemID = ItemID;
  };

  //Back event
  $scope.backEvent = function(ItemID, value) { 
    //sets the title in the modal 
    $scope.title = "back";
    $scope.amount = value;
    $scope.itemID = ItemID;
  };

  //modal 
  $scope.updateLostEvent = function(itemID, value, comment) { 

    var Indata = {'itemid': itemID, 'amount': value,'comment': comment, 'createdbyid': $localStorage.user_id}; //NEEDS TO BE IMPLEMENTED
    //Creates the url for the post
    var url = "/api/v1/restricted/rental/lost/" +  $scope.detailData[0][0].Id;
  
    //POST state device to the server
    $http.post(url, Indata).success(function(data, status) {
      //SUCCESSFULL //
      alert("success");
      //Reset variables
      $scope.amount_value = null;
      $scope.comment = "";
      //Gets/Reloads all item information of the loaded rental
      $scope.itemData = REST.detailRentalItemLoad({ListItemId: 'rental/SingleRentalsItems/' + $routeParams.ListItemId})
      //Gets/Reloads all user informations of a specific rental by id
      $scope.detailData = REST.detailRentalUserLoad({ListItemId: 'rental/SingleRentals/' + $routeParams.ListItemId});
  
    });
  };

  //modal 
  $scope.updateBackEvent = function(itemID, value, comment) { 
    var Indata = {'itemid': itemID, 'amount': value,'comment': comment, 'createdbyid': $localStorage.user_id}; //NEEDS TO BE IMPLEMENTED
    //Creates the url for the post
    var url = "/api/v1/restricted/rental/bringBack/" +  $scope.detailData[0][0].Id;

    //POST state device to the server
    $http.post(url, Indata).success(function(data, status) {
      //SUCCESSFULL //
      alert("success");
      //Reset variables
      $scope.amount_value = null;
      $scope.comment = "";
      //Gets/Reloads all item information of the loaded rental
      $scope.itemData = REST.detailRentalItemLoad({ListItemId: 'rental/SingleRentalsItems/' + $routeParams.ListItemId})
      //Gets/Reloads all user informations of a specific rental by id
      $scope.detailData = REST.detailRentalUserLoad({ListItemId: 'rental/SingleRentals/' + $routeParams.ListItemId});
  
    });
  };

}]);


//==============================
//Main-controller
//Used: overall other controller 
//==============================
invControllers.controller('indexCtrl', function ($scope, $location, $anchorScroll, $localStorage, $http) {

  //logout-button
  $scope.logout = function(){
      var tok = { 
        token: $localStorage.token
      }; 

      //send token to logout-api
      $http({
        method: 'POST',
        url: '/api/v1/logout',
        data: tok 
      })
      .then(
        function(response){},  
        function(err) {}    
      );

      //clear storage
      $localStorage.token = "";
      delete $localStorage.token;
      $localStorage.user_Id = "";
      delete $localStorage.user_id; 
  }

  //localwebstorage for the selected items
  $scope.selectedItems = [];

/* For the list / rental cart*/
  $scope.addItem = function(data) {  
    //check if state is not 0 and we dont select item twice   
    if(data.State == 1)
    {
      for (var i = 0; i < $scope.selectedItems.length; ++i) {    
        if ($scope.selectedItems[i].Id === data.Id) {
          return "error";
        }  
      }
      //push to item array
      $scope.selectedItems.push(data);
      return "success";
    }else{
      alert("Info: Item not available.");
      return "error";
    }
    
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




//==============================
//login-Controller
//used: login.html
//==============================

//sends login-data, gets and stores token
//decodes token, stores claims
invControllers.controller('loginCtrl', loginCtrl);
function loginCtrl($scope, $localStorage, $location, $http){

  //decode token function
  function base64Decode(str) {
     var output = str;

     //fill in spaces for base64-decoding
     switch (output.length % 4) {
         case 0:
             break;
         case 2:
             output += '==';
             break;
         case 3:
             output += '=';
             break;
         default:
             throw 'Illegal base64url string';
     }
     //'atob()' decodes base64
     return window.atob(output);
  }


  //split token and decode claims-part
  function getClaims() {
        var token = $localStorage.token;
        var user = {};

        if (typeof token !== 'undefined' || token != "" || token != null) {
          //get claims-part of token
            var encoded = token.split('.')[1];
            user = JSON.parse(base64Decode(encoded));
        }
        return user;
  }


  //sign-in Button
  $scope.signIn = function(){
    var userData = {
      email: $scope.email,
      password: $scope.password
    }; 

    var claims;

    $http({
      method: 'POST',
      url: '/api/v1/login',
      data: userData
    })
    .then(
      function(response){
        //store token
        $localStorage.token = response.data.token;
        //get token-claims
        claims = getClaims();
        //store user_id for easier use
        $localStorage.user_id = claims.User_Id;
        //relocate to dashboard
        $location.path('/dashboard');
      },  

      function(err) {
        //if wrong credentials, show error-msg
        $scope.error = {
          show: true
        }    
      }
    );
  }


}


//==============================
//invite Admin-Controller
//used: inviteAdmin.html
//==============================

//invite new admin: send email-adress to server
invControllers.controller('inviteAdminCtrl',['$scope', '$http', inviteAdminCtrl]);
function inviteAdminCtrl($scope, $http, $location){

  $scope.sendInvitation = function(email) {

    var email_adress = {
      "email" : email
    };

    $http({
      method: 'POST',
      url: '/api/v1/restricted/admin/invite', 
      data: email_adress
    })
    .then(
      function(re){ 
        alert("Invited Admin");
        $location.path('/dashboard');
      },         
      function(er){
        alert("An error occured. Please check if you used a valid email.");
      }
    );
  }
}



//==============================
//create new Admin - Controller
//used: createNewAdmin.html
//==============================

//sends sign-up data of new admin to server 
invControllers.controller('createNewAdminCtrl', createNewAdminCtrl);
function createNewAdminCtrl($scope, $location, $http){
  var tok = location.href.split('token=')[1];

  $scope.sendRegistration = function(){
    var newAdmin = {   
      "firstname": $scope.firstname,
      "lastname": $scope.lastname, 
      "street": $scope.street, 
      "city": $scope.city, 
      "zip": $scope.zip, 
      "mobilephone": $scope.phone,
      "matrikel": $scope.StudentID,
      "password": $scope.pw
    };
    
    $http({
      method: 'POST',
      url: '/api/v1/restricted/admin/create/' + tok, 
      data: newAdmin
    })
    .then(
      function(re){
        $location.path('/login');       
      },         
      function(er) {
        alert("something went wrong");
      }
    );  

  }  

}



//==============================
//reset password as admin -Controller
//used: resetPassword.html
//####### API missing ########
//==============================

//change password as signed-in Admin
invControllers.controller('resetPasswordAsAdminCtrl', resetPasswordCtrl);
function resetPasswordCtrl($scope, $http){
  //button
  $scope.changeOldPasswordAsAdmin = function(op, np) {
    var data = {
      oldpassword: op,
      newpassword: np
    };  

    //send old and new password
    $http({
      method: 'POST',
      url: '', 
      data: data
    })
    .then(
      function(re){},         
      function(er){
        alert("Please enter your correct current password.");
      }
    );

  } 
}




//==============================
//delete admin -Controller
//used: deleteAdmin.html
//==============================

//show active and inactive admins and delete them 
invControllers.controller('deleteAdminCtrl', deleteAdminCtrl);
function deleteAdminCtrl($scope, $http){

  //get index of a property and its value 
  function getIndex(array, property, targetvalue){
    for(var x=0; x < array.length; x++){
      if(array[x][property] == targetvalue){
        return x;
      }
    }
    return -1;
  }

  var allAdmins;

   //get admin-array
    $http({
      method: 'GET',
      url: '/api/v1/restricted/admin/allAdmins'
    })
    .then(
      function(re){
        $scope.listOfAdmins = re.data; 
        allAdmins = re.data;          
      },         
      function(er) {

      }
    );

   //delete-button 
    $scope.deleteAdmin = function(id){

      var ok = confirm("Are you sure you want to delete this admin?");
      if(ok){  
  
        $http({
          method: 'DELETE',
          url: '/api/v1/restricted/admin/deactivate/'+ id
        })
        .then(
          function(re){},         
          function(er){}
        );  
  
        //set admin-status to 0
        allAdmins[getIndex(allAdmins, "ID", id, 1)].Activated = 0;
        //update view-list
        allAdmins.splice(getIndex(allAdmins, "ID", id), 1);
        $scope.listOfAdmins = allAdmins;
      }
      ok = false;
    }

}



//==============================
//forgot passwort  -Controller
//used: forgotPassword.html, 
//      newPassword.html
//==============================

//used for not logged-in admins to change password via email-adress
invControllers.controller('forgotPasswordCtrl', forgotPasswordCtrl);
function forgotPasswordCtrl($scope, $http){

  //forgotpassword.html, send a link to email-adress
  $scope.sendEmail = function(mail){

    var email = {
      "email": mail
    };

    $http({
      method: 'POST',
      url: '',
      data: email
    })
    .then(
      function(re){},         
      function(er){}
      );          
  }

  //newPassword.html (will be sent with link to email-adress after using forgotPasswort.html)
  //link has a token in the url
  //sends new password to server
  $scope.sendPassword = function(newPw){

    //take token from url
    var tok = location.href.split('token=')[1];

    var data = {
      'password': newPw,
      'token': tok
    }; 

    $http({
      method: 'POST',
      url: '',
      data: data
    })
    .then(
      function(re){

      },         
      function(er) {

      }
      );          
  }


}


  //==============================
  //Category Management controller
  //Used: category.html
  //==============================
  invControllers.controller('CategoryCtrl', function ($scope, $http, $route) {

    //var nesseary for GET categories-array and nesting array 
    var allCategories = [];
    var categoryResult;
    var categoryTree;

    //GET categories-array
    $http({
      method: 'GET',
      url: '/api/v1/restricted/category/allCategory'
    }).then(function categorySuccess(response) {
        //handles success
        allCategories = response.data;

        //call functions to format query for rendering in html-template as nested list
        categoryResult = _queryTreeSort({q: allCategories});
        categoryTree = _makeTree({q: categoryResult});

        //for rendering nested list --> input is categoryTree
        $scope.nestedCategories = categoryTree;            
    }, function categoryError(response) {
        //handles error
        alert("An error occured. Could not load all categories.");
    });

    //for input fields and radio-buttons
    $scope.formData = {name: "", Parent: null, description: ""};

    //gets input from newValue 
    $scope.categories = {name: "1", description: "", children: ""};

    //changes input of categories when new radio-button is selected
    $scope.newValue = function(n, d, c) {
        $scope.categories = {name: n, description: d, children: c};
    };


    //==============================
    //EVENTS (categoryManagement)
    //==============================

    //==============================
    //update Category
    //==============================
    //in API erwartet 'Update a Category' die beforeID?
    $scope.updateCategoryEvent = function(categoryID, categoryName, categoryDescription) { 

      //alert(categoryName + " | " + categoryID + " | " + categoryDescription);
      var Indata = {'name': categoryName, 'description': categoryDescription};
      //POST updated category to server
      $http({
        method: 'POST',
        url: '/api/v1/restricted/category/update/' + categoryID,
        data: Indata 
      }).then(function updateSuccess(response) {
          //handles success
          alert("Selected category was updated."); 
          $route.reload();          
      }, function updateError(response) {
          //handles error
          alert("An error occured. Could not update category.");
      });
      //POST updated category to server
      /* $http.post("/api/v1/restricted/category/update/" + categoryID, Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //create Category
    //==============================
    $scope.createCategoryEvent = function(categoryID, categoryName, categoryDescription) { 

      //alert(categoryName + " | " + categoryID + " | " + categoryDescription);
      var Indata = {'name': categoryName, 'before': beforeID, 'description': categoryDescription, createdbyid: 3 };
      //POST new category to server
      $http({
        method: 'POST',
        url: '/api/v1/restricted/category/create',
        data: Indata 
      }).then(function createSuccess(response) {
          //handles success
          alert("New category was created.");
          $route.reload();           
      }, function createError(response) {
          //handles error
          alert("An error occured. Could not create new category.");
      });
      //POST new category to server
      /* $http.post("/api/v1/restricted/category/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //delete Category
    //==============================
    //in API überhaupt integriert? -> es ist sichergestellt, dass nur kategorien ohne kinder gelöscht werden
    $scope.deleteCategoryEvent = function(categoryID) { 

      //alert(categoryID);
      //DELETE category
      $http({
        method: 'DELETE',
        url: '/api/v1/restricted/category/delete/' + categoryID
      }).then(function deleteSuccess(response) {
          //handles success
          alert("Selected category was deleted.");
          $route.reload();           
      }, function deleteError(response) {
          //handles error
          alert("An error occured. Could not delete category.");
      });
      //DELETE category
      /* $http.delete("/api/v1/restricted/category/delete" + categoryID).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };  

  });


  //==============================
  //Place Management controller
  //Used: place.html
  //==============================
  invControllers.controller('PlaceCtrl', function ($scope, $http, $route) {

    //var nesseary for GET places-array and nesting array 
    var allPlaces = [];
    var placeResult;
    var placeTree;

    //GET places-array
    $http({
      method: 'GET',
      url: '/api/v1/restricted/place/allPlace'
    }).then(function placeSuccess(response) {
        //handles success
        allPlaces = response.data;

        //call functions to format query for rendering in html-template as nested list
        placeResult = _queryTreeSort({q: allPlaces});
        placeTree = _makeTree({q: placeResult});

        //for rendering nested list --> input is placeTree
        $scope.nestedPlaces = placeTree;            
    }, function placeError(response) {
        //handles error
        alert("An error occured. Could not load all places.");
    });

    //for input fields and radio-buttons
    $scope.formData = {name: "", Parent: null};

    //gets input from newValue 
    $scope.places = {name: "1", children: ""};

    //changes input of places when new radio-button is selected
    $scope.newValue = function(n, c) {
        $scope.places = {name: n,  children: c};
    };


    //==============================
    //EVENTS (locationManagement)
    //==============================


    //==============================
    //update Place
    //==============================
    $scope.updatePlaceEvent = function(placeName, placeID) { 

      //alert(placeName + " | " + palceID);
      var Indata = {'name': placeName};
      //POST updated place to server
      $http({
        method: 'POST',
        url: '/api/v1/restricted/place/update/' + placeID,
        data: Indata 
      }).then(function placeSuccess(response) {
          //handles success
          alert("Selected place was updated.");
          $route.reload();          
      }, function placeError(response) {
          //handles error
          alert("An error occured. Could not update place.");
      });
      //POST updated place to server
      /* $http.post("/api/v1/restricted/place/update/" + placeID, Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //create Place
    //==============================
    //in API erwarted CreatePlace die createdbyID als Input?
    $scope.createPlaceEvent = function(placeName, beforeID) { 

      //alert(placeName + " | " + beforeID);
      var Indata = {'name': placeName, 'before': beforeID, createdbyid: 3};
      //POST new place to server
      $http({
        method: 'POST',
        url: '/api/v1/restricted/place/create',
        data: Indata 
      }).then(function placeSuccess(response) {
          //handles success
          alert("New Place was created.");
          $route.reload();           
      }, function placeError(response) {
          //handles error
          alert("An error occured. Could not create new place.");
      });
      //POST new place to server
      /* $http.post("/api/v1/restricted/place/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //delete PLace
    //==============================
    //in API überhaupt integriert? -> es ist sichergestellt, dass nur plätze ohne kinder gelöscht werden
    $scope.deletePlaceEvent = function(placeID) { 

      //alert(placeID);
      //DELETE selected place
      $http({
        method: 'DELETE',
        url: '/api/v1/restricted/place/delete/' + placeID,
      }).then(function placeSuccess(response) {
          //handles success
          alert("Place was deleted.");
          $route.reload();          
      }, function placeError(response) {
          //handles error
          alert("An error occured. Could not delete place.");
      });
      //DELETE selected place
      /* $http.delete("/api/v1/restricted/place/delete/" + placeID).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };  

  });