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
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', '$location', '$http', 'REST', function($scope, $routeParams, $location, $http, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});
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
    var Indata = {'itemid': itemID, 'comment': comment, 'createdbyid': 1 }; //NEEDS TO BE IMPLEMENTED
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
invControllers.controller('CreateCtrl', ['$scope', '$routeParams', '$location', '$http', function($scope, $routeParams, $location, $http) {

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
                    'createdbyid': 1,                             //NEEDS TO BE IMPLEMENTED
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
                    'createdbyid': 1,                             //NEEDS TO BE IMPLEMENTED
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
}]);

//==============================
//ItemEdit controller
//Used: edit_item.html
//==============================
invControllers.controller('ItemEditCtrl', ['$scope', '$routeParams', '$location', '$http', 'REST', function($scope, $routeParams, $location, $http, REST) {
  //Gets all informations of a specific item by id
  $scope.detailData = REST.detailLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});

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
                    'createdbyid': 1,         //NEEDS TO BE IMPLEMENTED
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
                    'createdbyid': 1, //NEEDS TO BE IMPLEMENTED
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
invControllers.controller('RentalCtrl', ['$scope', '$routeParams', '$location', '$http', 'REST', function($scope, $routeParams, $location, $http, REST) {

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
                  'createdbyid': 1,             //NEEDS TO BE IMPLEMENTED
                  'comment': $scope.borrow.customer.comment,
                  'ids': [],
                  'amounts': []
                  };            
    
    for (var i = 0; i < $scope.selectedItems.length ; i++) {
      Indata.ids.push($scope.selectedItems[i].Id);
      Indata.amounts.push($scope.selectedItems[i].amount);
    }
    $scope.testvar = Indata;
    alert("converting ok");
    //POST rental to the server 
   $http.post("/api/v1/restricted/rental/create", Indata).success(function(data, status) {
      //SUCCESSFULL 
      alert("success material update");
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
  //$scope.listData = REST.allRental(); //need another api request here

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
invControllers.controller('RentalDetailCtrl', ['$scope', '$routeParams', '$location', 'REST', function($scope, $routeParams, $location, REST) {
  //Gets all informations of a specific item by id
  //$scope.detailData = REST.detailRentalLoad({ListItemId: 'item/details/' + $routeParams.ListItemId});
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




//###############
//loginController: sends login-data, gets and stores token, throws error if invalid userdata, routes to next page
invControllers.controller('loginCtrl', loginCtrl);
function loginCtrl($scope, $window, $location, $http){

  $scope.signIn = function(){
    var userData = {
      email: $scope.email,
      password: $scope.password
    }; 

    $http({
      method: 'POST',
      url: '/api/v1/login',
      data: userData
    })
    .then(
      function(response){
        //store token
        $window.localStorage.token = response.token;
        $location.path('/dashboard');
      },  
        //if no response throw error-msg
        function(err) {
          $scope.error = {
            show: true
          }    
        }
      );

  }

}

//invite new admin: sends email-adress to server
invControllers.controller('inviteAdminCtrl',['$scope', '$http', inviteAdminCtrl]);
function inviteAdminCtrl($scope, $http, $location){
  $scope.sendInvitation = function(email) {

    var email_adress = {
      "Email" : email
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

//ctrl which sends sign-up data of new admin to server 
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

//change password as signed-in Admin,####### API missing ########
invControllers.controller('resetPasswordAsAdminCtrl', resetPasswordCtrl);
function resetPasswordCtrl($scope, $http){
  $scope.changeOldPasswordAsAdmin = function(op, np) {
    var data = {
      oldPassword: op,
      newPassword: np
    };  

    //send old and new password
    $http({
      method: 'POST',
      url: '', 
      data: data
    })
    .then(
      function(re){

      },         
      function(er) {
        alert("Please enter your correct current password.");
      }
    );

  } 
}


//show active and inactive admins and delete them 
invControllers.controller('deleteAdminCtrl', deleteAdminCtrl);
function deleteAdminCtrl($scope, $http){

  function getIndex(array, property, targetvalue){
    for(var x=0; x < array.length; x++){
      if(array[x][property] == targetvalue){
        return x;
      }
    }
    return -1;
  }

  var allAdmins;

  //test: initialize list
    allAdmins = [{
      "ID":1,
      "FirstName": 'A',
      "LastName": 'B',
      "Email": 'abc',
      "Activated": 0
    },
    {
      "ID":12,
      "FirstName": 'E',
      "LastName": 'f',
      "Email": 'abc',
      "Activated": 0
    },
    {
      "ID":15,
      "FirstName": 'o',
      "LastName": 'p',
      "Email": 'abc',
      "Activated": 1
    },
    {
      "ID":19,
      "FirstName": 'z',
      "LastName": 'x',
      "Email": 'abc',
      "Activated": 1
    }];


  /*    //get admin-array
    $http({
      method: 'GET',
      url: '/api/v1/restricted/admin/allAdmins'
    })
    .then(
      function(re){
        allAdmins = re;            
      },         
      function(er) {

      }
    );
  */
    $scope.listOfAdmins = allAdmins;

   //button 
    $scope.deleteAdmin = function(id){
      var ok = confirm("Are you sure you want to delete this admin?");
      if(ok){  
  /*
        $http({
          method: 'DELETE',
          url: '/api/v1/restricted/admin/deactivate/'+ id
        })
        .then(
          function(re){

          },         
          function(er) {

          }
        );  
  */
        //update view-list
        allAdmins.splice(getIndex(allAdmins, "ID", id), 1);
        $scope.listOfAdmins = allAdmins;
      }
      ok = false;
    }

}



//"forgot-password"-function at login.html, ####### 2 API MISSING #######
invControllers.controller('forgotPasswordCtrl', forgotPasswordCtrl);
function forgotPasswordCtrl($scope, $http){

  //forgotpassword.html, send a link to email-adress
  $scope.sendEmail = function(mail){

    var email = {
      "Email": mail
    };

    $http({
      method: 'POST',
      url: '',
      data: email
    })
    .then(
      function(re){

      },         
      function(er) {

      }
      );          
  }

  //newPassword.html (Link from "forgotPassword.html"), sends new password to server
  $scope.sendPassword = function(newPw){
    var tok = location.href.split('token=')[1];

    var data = {
      'Password': newPw,
      'Token': tok
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
  invControllers.controller('CategoryCtrl', function ($scope) {

    //Data for testing
    //it might be a proplem if Data from Server is like this {[id: 1, name: "tool", before: null], [...], ...}
    var test = [
      {"id": 1, "Name": "Werkzeug", "Description": "Tools you can use with one hand", "BeforeID": null, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 2, "Name": "Bohrer", "Description": "Tools you can use with one hand", "BeforeID": 1, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 3, "Name": "Bohrer A", "Description": "Tools you can use with one hand", "BeforeID": 2, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 4, "Name": "Bohrer B", "Description": "Tools you can use with one hand", "BeforeID": 2, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 5, "Name": "TGA", "Description": "Tools you can use with one hand", "BeforeID": 3, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 6, "Name": "Kondensator", "Description": "Tools you can use with one hand", "BeforeID": 7, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 7, "Name": "elekt. Bauteil", "Description": "Tools you can use with one hand", "BeforeID": null, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"}
    ];

    //call functions to format query for rendering in html-template as nested list
    var result = _queryTreeSort({q: test});
    var tree = _makeTree({q: result});

    //for rendering nested list --> input is tree
    $scope.children = tree;

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
    $scope.updateCategoryEvent = function(categoryName, beforeID, categoryDescription) { 

      alert(categoryName + " | " + beforeID + " | " + categoryDescription);
      var Indata = {'name': categoryName, 'before': beforeID, 'description': categoryDescription };
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //create Category
    //==============================
    $scope.createCategoryEvent = function(categoryName, beforeID, categoryDescription) { 

      alert(categoryName + " | " + beforeID + " | " + categoryDescription);
      var Indata = {'name': categoryName, 'before': beforeID, 'description': categoryDescription };
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //delete Category
    //==============================
    //in API überhaupt integriert? -> es ist sichergestellt, dass nur kategorien ohne kinder gelöscht werden
    $scope.deleteCategoryEvent = function(categoryID) { 

      alert(categoryID);
      var Indata = {'id': categoryID };
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };  

  });


  //==============================
  //Place Management controller
  //Used: place.html
  //==============================
  invControllers.controller('PlaceCtrl', function ($scope) {

    //Data for testing
    //it might be a proplem if Data from Server is like this {[id: 1, name: "tool", before: null], [...], ...}
    var test = [
      {"id": 1, "Name": "Haus M", "CreatedByID": 4, "BeforeID": null, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 2, "Name": "Raum 101", "CreatedByID": 4, "BeforeID": 1, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 3, "Name": "Schrank A", "CreatedByID": 4, "BeforeID": 2, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 4, "Name": "Schrank B", "CreatedByID": 4, "BeforeID": 2, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 5, "Name": "Fach III", "CreatedByID": 4, "BeforeID": 3, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 6, "Name": "Oeconomicum", "CreatedByID": 4, "BeforeID": 7, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"},
      {"id": 7, "Name": "Raum 118", "CreatedByID": 4, "BeforeID": null, "created_at": "2016-06-07 13:59:31", "updated_at": "2016-06-07 13:59:31"}
    ];

    //call functions to format query for rendering in html-template as nested list
    var result = _queryTreeSort({q: test});
    var tree = _makeTree({q: result});

    //for rendering nested list --> input is tree
    $scope.children = tree;

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
    $scope.updatePlaceEvent = function(placeName, beforeID) { 

      alert(placeName + " | " + beforeID);
      var Indata = {'name': categoryName, 'before': beforeID};
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //create Place
    //==============================
    //in API erwarted CreatePlace die createdbyID als Input?
    $scope.createPlaceEvent = function(palceName, beforeID) { 

      alert(palceName + " | " + beforeID);
      var Indata = {'name': placeName, 'before': beforeID};
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };

    //==============================
    //delete PLace
    //==============================
    //in API überhaupt integriert? -> es ist sichergestellt, dass nur plätze ohne kinder gelöscht werden
    $scope.deletePlaceEvent = function(placeID) { 

      alert(placeID);
      var Indata = {'id': placeID };
      //POST used material to the server
      /* $http.post("/api/v1/restricted/device/create", Indata).success(function(data, status) {
      //SUCCESSFULL
      alert("success");
      });*/
    };  

  });