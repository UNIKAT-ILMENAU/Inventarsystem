'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination', 'ngStorage']);

//==============================
//Request Admin list
//Description: The main-site controller, loads the list and controls the pagination, aswell the route to the detailview of an item
//Used in: list.html
//==============================
invControllers.controller('ListCtrl', function ($scope, $location, ItemResource) {
    $scope.currentPage = 1;

    reloadData();

    function reloadData() {
        //Get all item informations from the server
        ItemResource.allItems({"page": $scope.currentPage, "orderBy": $scope.sortKey, "reverse": $scope.reverse, "search": $scope.searchQuery}, function success(result) {
            $scope.paginationData = result;
            $scope.listData = $scope.paginationData.data;

            $scope.pageList = [];
            for(var i = 1; i <= $scope.paginationData.last_page ; i++) {
                $scope.pageList.push({"index": i});
            }
        });
    }

    $scope.search = function () {
        reloadData();
    };

    $scope.sort = function (keyname) {    //sort option on click, call by reference
        $scope.sortKey = keyname;         //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa

        reloadData();
    };

    $scope.nextPage = function () {
        if($scope.currentPage == $scope.paginationData.last_page) {
            return;
        }
        $scope.currentPage += 1;
        reloadData();
    };

    $scope.previousPage = function () {
        if($scope.currentPage == 1) {
            return;
        }
        $scope.currentPage -= 1;
        reloadData();
    };

    $scope.loadPage = function (i) {
        $scope.currentPage = i;
        reloadData();
    };

    //Creates a new Item without an copy
    $scope.createNewItem = function (typ) {
        $scope.clearItem(); //clears the rent-cart

        //Link us to the right form
        if (typ == "Device") {
            $location.path('/create_device');
        }
        else {
            $location.path('/create_material');
        }
    };

    //Loads the rental form
    $scope.loadRental = function () {
        $location.path('/rental');
    };

    //Loads the detailView form
    $scope.viewDetail = function (listID) {    //tr clickable, change to detailview view, activated via 1click
        $location.path('/listData/' + listID);
    };

    //Resets all filter options in the list
    $scope.resetFilter = function () {
        $scope.search = "";   //resets the filter options
        $scope.pageSize = d_pageSize; //resets the items per page size to default
    };

});

//==============================
//Menu controller handles menu functions
//Used in: dashboard.html - adminconf_menu.html - systemconf_menu.html 
//==============================
invControllers.controller('MenuCtrl', function ($scope, DashboardResource) {
    //Gets all dashboard information
    $scope.dashData = DashboardResource.items();

});

//==============================
//Request Detail informations from specific item
//Used in: detail.html 
//==============================
invControllers.controller('DetailCtrl', ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'ItemResource', function ($scope, $localStorage, $routeParams, $location, $http, ItemResource) {
    //Gets all informations of a specific item by id
    $scope.detailData = ItemResource.detailLoad({id: $routeParams.ListItemId});
    //Gets the place as a string
    // $scope.Place_name = ItemResource.place({id: $routeParams.ListItemId});
    //Gets all history informations of a specific item by id
    $scope.historyData = ItemResource.historyLoad({id: $routeParams.ListItemId});

    //Reloads all the data of a specific item by id (reloads detailView data)
    $scope.ReloadDatas = function () {
        //Gets all informations of a specific item by id
        $scope.detailData = ItemResource.detailLoad({id: $routeParams.ListItemId});
        //Gets all history informations of a specific item by id
        $scope.historyData = ItemResource.historyLoad({id: $routeParams.ListItemId});

        $scope.amount = '';
        $scope.comment = '';
    };

    //==============================
    //Simple Alert System (Work in progress)
    //==============================
    $scope.alert = [];
    $scope.addAlert = function (info) {
        if (info == 'error') {
            $scope.alert.push({msg: 'Error please try again!'});
        } else {
            $scope.alert.push({msg: 'Action successful'});
        }
    };

    //==============================
    //EVENTS (DetailView)
    //==============================
    $scope.rentalAdd = function (data) {
        $scope.addAlert($scope.addItem(data, false));
    };

    //Link to edit item form
    $scope.editItem = function (listID) {
        //link us to the edit form of the selected item by id
        $location.path('/edit_item/' + listID);
    };

    //Link to copy item form
    $scope.copyItem = function (data, info) {

        $scope.clearItem();   //clear the rental cart
        $scope.addItem(data, true); //adds the item that we can use it

        //Link to the right create form
        if (info == 'Device') {
            $location.path('/create_device');
        }
        else if (info == 'Material') {
            $location.path('/create_material');
        }
    };

    //==============================
    //device defect / missing state update
    //==============================
    $scope.deviceEvent = function (info, stateID) {
        //sets the title in the devicemodal
        $scope.title = info;
        $scope.state = stateID;
    };

    //modal function for the device state update (defect/missing)
    $scope.updateStateEvent = function (itemID, stateID, comment) {
        var Indata = {
            'comment': comment
        };

        if (stateID == 2) {

            ItemResource.defective({id: itemID}, Indata).$promise.then(function () {
                $scope.ReloadDatas();
            });

        } else if (stateID == 3) {
            ItemResource.missing({id: itemID}, Indata).$promise.then(function () {
                $scope.ReloadDatas();
            });
        } else if (stateID == 1) {
            ItemResource.available({id: itemID}, Indata).$promise.then(function () {
                $scope.ReloadDatas();
            });
        }
    };

    //==============================
    //material used / stockup function
    //==============================
    $scope.materialEvent = function (info) {
        //sets the title in the materialmodal
        $scope.title = info;
    };

    //modal function for the material used / stockup function
    $scope.updateMaterialEvent = function (itemID, title, amount, comment) {
        //check event and if we have a positiv amount
        console.log("bla", itemID, title, amount, comment)
        var Indata = {};
        if (title == "used" && amount > 0) {
            Indata = {'amount': amount, 'comment': comment};

            ItemResource.use({id: itemID}, Indata).$promise.then(function () {
                $scope.ReloadDatas();
            });

        } //check event and if we have a positiv amount
        else if (title == "stock up" && amount > 0) {
            Indata = {'amount': amount, 'comment': comment};

            ItemResource.restock({id: itemID}, Indata).$promise.then(function () {
                $scope.ReloadDatas();
            });
        }
    };

}]);

//==============================
//Create Item controller
//Used: create_material.html, create_device.html 
//==============================
invControllers.controller('CreateCtrl',
    ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'dataFactory', 'PlaceResource', 'CategoryResource', 'ItemResource',
        function ($scope, $localStorage, $routeParams, $location, $http, dataFactory, PlaceResource, CategoryResource, ItemResource) {

    console.log($scope.selectedItems);

    if ($scope.selectedItems.length == 0) {
        $scope.selectedItems[0] = {};
    }

    $scope.selectedItems[0].Comment = "Neu angelegt";

    //Send create to the server
    $scope.createItemToServer = function (typ) {

        if (typ == "Device") //Create Device
        {
            var Indata = {
                'name': $scope.selectedItems[0].name,
                'state': $scope.selectedItems[0].state,
                'description': $scope.selectedItems[0].description,
                'category_id': $scope.selectedItems[0].category_id,
                'visible': $scope.selectedItems[0].visible,
                'place_id': $scope.selectedItems[0].place_id,
                'comment': $scope.selectedItems[0].Comment,
                'type': 'DEVICE'
            };
            ItemResource.create(Indata, function onSuccess(response) {
                $scope.clearItem();
                $location.path('/list');
            });
        }
        else  //Create Material
        {
            var Indata = {
                'name': $scope.selectedItems[0].name,
                'state': $scope.selectedItems[0].state,
                'category_id': $scope.selectedItems[0].category_id,
                'description': $scope.selectedItems[0].description,
                'visible': $scope.selectedItems[0].visible,
                'sale_price': $scope.selectedItems[0].sale_price,
                'place_id': $scope.selectedItems[0].place_id,
                'build_type': $scope.selectedItems[0].build_type,
                'uom': $scope.selectedItems[0].uom,
                'uom_short': $scope.selectedItems[0].uom_short,
                'storage_value': $scope.selectedItems[0].storage_value,
                'critical_storage_value': $scope.selectedItems[0].critical_storage_value,
                'comment': $scope.selectedItems[0].Comment,
                'type': 'MATERIAL'
            };

            ItemResource.create(Indata, function onSuccess(response) {
                $scope.clearItem();
                $location.path('/list');
            });
        }
    };

    //Scope sets selectState[0] when reset-button is pressed
    $scope.resetItem = function () {
        $scope.selectedItems[0] = {"State": 1, "PublicVisible": 1};
    };

    //=========================================
    //Options and default values for dropdowns
    //=========================================

    //options and default('available') for state in create_device
    $scope.deviceStates = [{name: 'Not available', value: 0},
        {name: 'Available', value: 1},
        {name: 'Defective', value: 2},
        {name: 'Missing', value: 3},
        {name: 'Rented', value: 4}
    ];

    //options and default('available') for state in create_material
    $scope.materialStates = [{name: 'Not available', value: 0},
        {name: 'Available', value: 1}
    ];

    //options and default('Visible') for PublicVisible in create_material/create_device
    $scope.Visibility = [{name: 'Not visible', value: 0},
        {name: 'Visible', value: 1}
    ];

    //==============================
    //Get all places from server
    //==============================

    $scope.nestedPlaces = PlaceResource.all();

    //changes input of selectedItems[0].PlaceName when new radio-button is selected
    $scope.newPlaceValue = function (n) {
        $scope.selectedItems[0].PlaceName = n;
    };

    //==============================
    //Get all categories from server
    //==============================

    $scope.nestedCategories = CategoryResource.all();


    //changes input of selectedItems[0].Category when new radio-button is selected
    $scope.newCategoryValue = function (n) {
        $scope.selectedItems[0].Category = n;
    };

}]);

//==============================
//ItemEdit controller
//Used: edit_item.html
//==============================
invControllers.controller('ItemEditCtrl', ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'ItemResource', 'dataFactory', 'CategoryResource', 'PlaceResource',
    function ($scope, $localStorage, $routeParams, $location, $http, ItemResource, dataFactory, CategoryResource, PlaceResource) {

    console.log("ItemdEditCtrl");

    //Gets all informations of a specific item by id
    $scope.detailData = ItemResource.detailLoad({id: $routeParams.ListItemId});

    $scope.nestedCategories = CategoryResource.all();

    $scope.nestedPlaces = PlaceResource.all();



    //=========================================
    //Options and default values for dropdowns
    //=========================================
    //options and default('available') for state of device in edit_item.html
    $scope.deviceStates = [{name: 'Not available', value: 0},
        {name: 'Available', value: 1},
        {name: 'Defective', value: 2},
        {name: 'Missing', value: 3},
        {name: 'Rented', value: 4}
    ];

    //options and default('available') for state of material in edit_item.html
    $scope.materialStates = [{name: 'Not available', value: 0},
        {name: 'Available', value: 1}
    ];

    //options and default('Visible') for PublicVisible in edit_item.html
    $scope.Visibility = [{name: 'Not visible', value: 0},
        {name: 'Visible', value: 1}
    ];

    //Update/Edit item to the server
    $scope.saveEdit = function () {
        if ($scope.detailData.type == 'DEVICE') //Update Device
        {
            var Indata = {
                'name': $scope.detailData.name,
                'state': $scope.detailData.state,
                'description': $scope.detailData.sescription,
                'category_id': $scope.detailData.category_id,
                'visible': $scope.detailData.visible,
                'place_id': $scope.detailData.place_id,
                'comment': $scope.detailData.comment,
                'type': 'DEVICE'
            };
            ItemResource.update({id: $scope.detailData.id}, Indata, function onSuccess(response) {
                $location.path('/listData/' + $scope.detailData.id);
            });
        }
        else  //Update Material
        {
            var Indata = {
                'name': $scope.detailData.name,
                'state': $scope.detailData.state,
                'category_id': $scope.detailData.category_id,
                'description': $scope.detailData.description,
                'visible': $scope.detailData.visible,
                'sale_price': $scope.detailData.sale_price,
                'place_id': $scope.detailData.place_id,
                'build_type': $scope.detailData.build_type,
                'uom': $scope.detailData.uom,
                'uom_short': $scope.detailData.uom_short,
                'storage_value': $scope.detailData.storage_value,
                'critical_storage_value': $scope.detailData.critical_storage_value,
                'comment': $scope.detailData.comment,
                'type': 'MATERIAL'
            };

            ItemResource.update({id: $scope.detailData.id}, Indata, function onSuccess(response) {
                $location.path('/listData/' + $scope.detailData.id);
            });
        }
    };

    $scope.viewDetail = function (listID) {        //change to detailview view
        $location.path('/listData/' + listID);  //redirect to detailView
    };

}]);


//==============================
//Rental controller
//Used: rental.html 
//==============================
invControllers.controller('RentalCtrl', ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'RentalResource', function ($scope, $localStorage, $routeParams, $location, $http, RentalResource) {

    //redirect us, when we are accidentally on the rental page
    if ($scope.selectedItems[0] == null) {
        $location.path('/list');
    }

    //This is our rental object with all informations about the current-rental_cart
    $scope.borrow =
        {
            'customer': {
                'name': '',
                'phone': '',
                'email': '',
                'enddate': '',
                'comment': 'Normale Ausleihe'
            }
        };

    $scope.sendRental = function () {
        //needs to be like this cause datepicker doesnt work with ng-change
        $scope.borrow.customer.enddate = document.getElementById("enddate").value;

        var Indata = {
            'name': $scope.borrow.customer.name,
            'phone': $scope.borrow.customer.phone,
            'email': $scope.borrow.customer.email,
            'enddate': $scope.borrow.customer.enddate,
            'comment': $scope.borrow.customer.comment,
            'items': []
        };

        for (var i = 0; i < $scope.selectedItems.length; i++) {
            var item = {};
            item.id = $scope.selectedItems[i].id;
            item.amount = $scope.selectedItems[i].amount;

            Indata.items.push(item);
        }

        RentalResource.create(Indata).$promise.then(function () {
            $location.path('/rentallist');
        });

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
invControllers.controller('RentalListCtrl', ['$scope', '$routeParams', '$location', 'RentalResource', function ($scope, $routeParams, $location, RentalResource) {
    //Get all item informations from the server
    $scope.listData = RentalResource.all();

    var d_pageSize = 10;                    //default pageSize limit
    $scope.pageSize = d_pageSize;           //Item limit per page

    $scope.sort = function (keyname) {    //sort option on click, call by reference
        $scope.sortKey = keyname;         //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    };

    //Loads the detailView form
    $scope.viewDetail = function (rentalID) {    //tr clickable, change to detailview view, activated via 1click
        $location.path('/rentalData/' + rentalID);
    };

    //Resets all filter options in the list
    $scope.resetFilter = function () {
        $scope.search = "";   //resets the filter options
        $scope.pageSize = d_pageSize; //resets the items per page size to default
    };

    //Compares EndDate of rental with current date
    $scope.dateCompare = function (endDate) {

        //set EndDate of rental string in correct format
        var partsTimestamp = endDate.split(/[ \/:-]/g);
        if (partsTimestamp.length < 6) {
            partsTimestamp = partsTimestamp.concat(['00', '00', '00'].slice(0, 6 - partsTimestamp.length));
        }
        var tstring = partsTimestamp.slice(0, 3).join('-');
        tstring += 'T' + partsTimestamp.slice(3).join(':') + 'Z'; //configure as needed

        //set current date in correct format
        var currentDate = new Date();
        currentDate.setHours(0);
        currentDate.setMinutes(0);
        currentDate.setSeconds(0);

        //parse dates in milliseconds and subtract them
        var currentMS = currentDate.getTime();
        var enddateMS = Date.parse(tstring);
        var dif = enddateMS - currentMS;

        if (dif <= 0) {                 //EndDate <= current date
            var output = 0;
        } else if (dif < 604800000) {   //EnDate <= current date + one week
            var output = 1;
        } else {                        //else
            var output = 2;
        }

        return output;
    };

}]);

//==============================
//Rental detail controller
//Used: rental_detail.html
//==============================
invControllers.controller('RentalDetailCtrl', ['$scope', '$localStorage', '$routeParams', '$location', '$http', 'RentalResource', function ($scope, $localStorage, $routeParams, $location, $http, RentalResource) {

    $scope.detailData = RentalResource.detailLoad({id: $routeParams.ListItemId});

    //==============================
    //Item rented events
    //==============================
    //bugfixing modal bug
    $scope.amount_value;

    $scope.change_amount = function (value) {
        $scope.amount_value = value;
    };

    //Lost event
    $scope.lostEvent = function (ItemID, value) {
        //sets the title in the modal
        $scope.title = "lost";
        $scope.comment = "";
        $scope.amount = value;
        $scope.itemID = ItemID;
    };

    //Back event
    $scope.backEvent = function (ItemID, value) {
        //sets the title in the modal
        $scope.title = "back";
        $scope.comment = "Heile zurück";
        $scope.amount = value;
        $scope.itemID = ItemID;
    };

    //modal function for the lost event
    $scope.updateLostEvent = function (itemID, value, comment) {

        var Indata = {
            'itemid': itemID,
            'amount': value,
            'comment': comment
        };

        RentalResource.lostItem({id: $routeParams.ListItemId}, Indata).$promise.then(function () {
            $scope.detailData = RentalResource.detailLoad({id: $routeParams.ListItemId});
        });


    };

    //modal function for the back event
    $scope.updateBackEvent = function (itemID, value, comment) {
        var Indata = {
            'itemid': itemID,
            'amount': value,
            'comment': comment,
        };

        RentalResource.returnItem({id: $routeParams.ListItemId}, Indata).$promise.then(function () {
            $scope.detailData = RentalResource.detailLoad({id: $routeParams.ListItemId});
        });
    };

    //Compares EndDate of rental with current date
    $scope.dateCompare = function (endDate) {

        //set EndDate of rental string in correct format
        var partsTimestamp = endDate.split(/[ \/:-]/g);
        if (partsTimestamp.length < 6) {
            partsTimestamp = partsTimestamp.concat(['00', '00', '00'].slice(0, 6 - partsTimestamp.length));
        }
        var tstring = partsTimestamp.slice(0, 3).join('-');
        tstring += 'T' + partsTimestamp.slice(3).join(':') + 'Z'; //configure as needed

        //set current date in correct format
        var currentDate = new Date();
        currentDate.setHours(0);
        currentDate.setMinutes(0);
        currentDate.setSeconds(0);

        //parse dates in milliseconds and subtract them
        var currentMS = currentDate.getTime();
        var enddateMS = Date.parse(tstring);
        var dif = enddateMS - currentMS;

        if (dif <= 0) {                 //EndDate <= current date
            var output = 0;
        } else if (dif < 604800000) {   //EnDate <= current date + one week
            var output = 1;
        } else {                        //else
            var output = 2;
        }

        return output;
    };

}]);


//==============================
//Main-controller
//Used: overall other controller 
//==============================
invControllers.controller('indexCtrl', function ($scope, $http, $localStorage, $location, $anchorScroll) {
//logout-button
    $scope.logout = function () {
        var tok = {
            token: $localStorage.token
        };

        //send token to logout-api
        $http({
            method: 'POST',
            url: '../api/v1/restricted/logout',
            data: tok
        })
            .then(
                function (response) {
                },
                function (err) {
                }
            );

        window.localStorage.clear();
    }

    //localwebstorage for the selected items
    $scope.selectedItems = [];

    /* For the list / rental cart*/
    $scope.addItem = function (data, forCopy) {
        //check if (state is not 0 or forCopy is true) and we dont select item twice
        if (data.state == 1 || forCopy == true) {
            for (var i = 0; i < $scope.selectedItems.length; ++i) {
                if ($scope.selectedItems[i].id === data.id) {
                    return "error";
                }
            }
            //push to item array
            $scope.selectedItems.push(data);
            return "success";
        } else {
            alert("Info: Item not available.");
            return "error";
        }

    };

    $scope.removeItem = function (index) {

        $scope.selectedItems.splice(index, 1);
        //redirect us when we dont have items in rentallist and are not in the listview
        if ($scope.selectedItems[0] == null && $location.url() != '/list') {
            $location.path('/list');
        }
    };

    $scope.clearItem = function () {

        $scope.selectedItems = [];
    };

    /* AUTOSTART EVENT
     $scope.$on('$viewContentLoaded', function(){

     });
     */

    $scope.scrollTo = function () {
        // set the location.hash to null/top
        $location.hash();

        // call $anchorScroll() to use the scroll
        $anchorScroll();
    };

    //check if we are on the login page
    $scope.isLoginPage = function () {
        if ($location.url() == '/' || $location.url() == '/login') {
            return true;
        }
        return false;
    };
});


//==============================
//login-Controller
//used: login.html
//==============================

//sends login-data, gets and stores token
//decodes token, stores claims
invControllers.controller('loginCtrl', loginCtrl);
function loginCtrl($scope, $localStorage, $location, $http) {

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
    $scope.signIn = function () {
        var userData = {
            email: $scope.email,
            password: $scope.password
        };

        var claims;


        $http({
            method: 'POST',
            url: '../api/v1/login',
            data: userData
        })
            .then(
                function (response) {
                    //store token
                    $localStorage.token = response.data.token;
                    //get token-claims
                    claims = getClaims();
                    //store user_id for easier use
                    $localStorage.user_id = claims.User_Id;
                    //relocate to dashboard
                    $location.path('/dashboard');
                },

                function (err) {
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
invControllers.controller('inviteAdminCtrl', inviteAdminCtrl);
function inviteAdminCtrl($localStorage, $scope, $http, $location) {
    //send-button
    $scope.sendInvitation = function () {

        var email_adress = {
            "email": $scope.email
        };


        $http({
            method: 'POST',
            url: '../api/v1/restricted/admin/invite',
            data: email_adress
        })
            .then(
                function (re) {
                    alert("Invited Admin");
                    $location.path('/dashboard');
                },
                function (er) {
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
function createNewAdminCtrl($scope, $location, $http) {
    //get token from url
    var tok = location.href.split('token=')[1];

    $scope.sendRegistration = function () {
        var newAdmin = {
            "name": $scope.name,
            "password": $scope.pw,
            "email": $scope.email,
            "token": tok
        };

        $http({
            method: 'POST',
            url: '../api/v1/newUser',
            data: newAdmin
        })
            .then(
                // TODO, does not fit to server response
                function (re) {

                    if (re.data.error == "Email not found")
                        alert("Email not found");
                    else if (re.data.error == "Email found. But Token invalid")
                        alert("Email found. But Token invalid");
                    else
                        $location.path('/login');
                },
                function (er) {
                    alert("something went wrong");
                }
            );
    }
}


//==============================
//reset password as admin -Controller
//used: resetPassword.html
//==============================

//change password as signed-in Admin
invControllers.controller('resetPasswordAsAdminCtrl', resetPasswordCtrl);
function resetPasswordCtrl($scope, $http, $location, UserResource) {

    $scope.user = UserResource.me();

    //change-button
    $scope.changePasswordAsAdmin = function () {
        var data = {
            currentpassword: $scope.oldPw,
            newpassword: $scope.newPw,
            name: $scope.user.name
        };

        UserResource.update({id: $scope.user.id}, data, function onSuccess(resp) {
            $scope.user = UserResource.me();
        }, function onError(resp) {
            console.error("Error updating user", resp);
        });

    }
}


//==============================
//delete admin -Controller
//used: deleteAdmin.html
//==============================

//show active and inactive admins and delete them 
invControllers.controller('deleteAdminCtrl', deleteAdminCtrl);
function deleteAdminCtrl($scope, $http, UserResource) {

    //get index of a property and its value
    function getIndex(array, property, targetvalue) {
        for (var x = 0; x < array.length; x++) {
            if (array[x][property] == targetvalue) {
                return x;
            }
        }
        return -1;
    }

    var allAdmins;

    $scope.listOfAdmins = UserResource.all();


    //delete-button
    $scope.deleteAdmin = function (id) {

        var ok = confirm("Are you sure you want to delete this admin?");
        if (ok) {

            UserResource.deactivate({id: id}, {}, function () {
                $scope.listOfAdmins = UserResource.all();
            });

        }
        ok = false;
    };

    $scope.enableAdmin = function (userId) {
        UserResource.activate({id: userId}, {}, function () {
            $scope.listOfAdmins = UserResource.all();
        });
    }

}


//==============================
//forgot passwort  -Controller
//used: forgotPassword.html, 
//      newPassword.html
//==============================

//used for not logged-in admins to change password via email-adress
invControllers.controller('forgotPasswordCtrl', forgotPasswordCtrl);
function forgotPasswordCtrl($scope, $http, $location) {

    //forgotpassword.html, send a link to email-adress
    $scope.sendEmail = function () {

        var email = {
            "email": $scope.email
        };

        $http({
            method: 'POST',
            url: '../api/v1/sendPassword',
            data: email
        })
            .then(
                function (re) {
                    alert('Email sended.');
                    $location.path('/login');
                },
                function (er) {
                }
            );
    }

    //newPassword.html (will be sent with link to email-adress after using forgotPasswort.html)
    //link has a token in the url
    //sends new password to server
    $scope.sendPassword = function () {

        //take token from url
        var tok = location.href.split('token=')[1];

        var data = {
            'email': $scope.mail,
            'password': $scope.newPw,
            'token': tok
        };

        $http({
            method: 'POST',
            url: '../api/v1/resetPassword',
            data: data
        })
            .then(
                function (re) {
                    $location.path('/login');
                },
                function (er) {
                }
            );
    }
}


//==============================
//Category Management controller
//Used: category.html
//==============================
invControllers.controller('CategoryCtrl', function ($scope, $localStorage, $http, $route, dataFactory, CategoryResource) {

    var categories = [{id: null, name: "ROOT", parent: null}];
    categories[0].children = CategoryResource.all();
    $scope.nestedCategories = categories;


    //for input fields and radio-buttons
    $scope.formData = {name: "", Parent: null, description: ""};

    //gets input from newValue 
    $scope.categories = {name: "1", description: "", children: ""};

    //changes input of categories when new radio-button is selected
    $scope.newValue = function (n, d, c) {
        $scope.categories = {name: n, description: d, children: c};
    };


    //==============================
    //EVENTS (categoryManagement)
    //==============================

    //==============================
    //update Category
    //==============================
    $scope.updateCategoryEvent = function (categoryID, categoryName, categoryDescription) {

        var Indata = {'name': categoryName, 'description': categoryDescription};
        CategoryResource.update({id: categoryID}, Indata, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't update category", response);
        });
    };

    //==============================
    //create Category
    //==============================
    $scope.createCategoryEvent = function (categoryID, categoryName, categoryDescription) {

        //alert(categoryName + " | " + categoryID + " | " + categoryDescription);
        var Indata = {
            'name': categoryName,
            'parent': categoryID,
            'description': categoryDescription,
        };
        CategoryResource.create(Indata, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't create category", response);
        });
    };

    //==============================
    //delete Category
    //==============================
    $scope.deleteCategoryEvent = function (categoryID) {

        CategoryResource.delete({id: categoryID}, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't delete category", response);
        });
    };

});


//==============================
//Place Management controller
//Used: place.html
//==============================
invControllers.controller('PlaceCtrl', function ($scope, $http, $localStorage, $route, dataFactory, PlaceResource) {

    var places = [{id: null, name: "ROOT", parent: null}];
    places[0].children = PlaceResource.all();
    $scope.nestedPlaces = places;

    //for input fields and radio-buttons
    $scope.formData = {name: "", Parent: null};

    //gets input from newValue 
    $scope.places = {name: "1", children: ""};

    //changes input of places when new radio-button is selected
    $scope.newValue = function (n, c) {
        $scope.places = {name: n, children: c};
    };


    //==============================
    //EVENTS (locationManagement)
    //==============================

    //==============================
    //update Place
    //==============================
    $scope.updatePlaceEvent = function (placeName, placeID) {

        var Indata = {'name': placeName};
        PlaceResource.update({id: placeID}, Indata, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't update Place", response);
        });

    };

    //==============================
    //create Place
    //==============================
    $scope.createPlaceEvent = function (placeName, beforeID) {

        var Indata = {'name': placeName, 'parent': beforeID};
        PlaceResource.create(Indata, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't create Place", response);
        });
    };

    //==============================
    //delete PLace
    //==============================
    $scope.deletePlaceEvent = function (placeID) {

        PlaceResource.delete({id: placeID}, function onSuccess(response) {
            $route.reload();
        }, function onError(response) {
            console.error("Can't create Place", response);
        });
    };

});