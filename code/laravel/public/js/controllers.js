'use strict';

/* AllControllers */

var invControllers = angular.module('invControllers', ['angularUtils.directives.dirPagination']);

//==============================
//Request list
//Description: The main-site controller, loads the list and controls the pagination, aswell the route to the detailview of an item
//Used in: list.html
//==============================
invControllers.controller('ListCtrl', function ($scope, $location, $http, ItemResource) {

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

    // //Get all item informations from the server
    // var allItems = ItemResource.allItems(function () {
    //     var newAllItems = allItems.map(function (element) {
    //
    //         return {
    //             Id: element.id,
    //             Name: element.name,
    //             State: element.state,
    //             // material_id: element.type,
    //             Category: element.category ? element.category.name : null,
    //             BuildType: element.build_type,
    //             SalePrice: element.sale_price,
    //             StorageValue: element.storage_value,
    //             Type: element.type
    //         }
    //     });
    //
    //     $scope.listData = newAllItems;
    // });

    $scope.viewDetail = function (listID) {    //tr clickable, change to detailview view, activated via 1click
        $location.path('/listData/' + listID);
    };

});

//==============================
//Request Detail informations from specific item
//Used in: detail.html 
//==============================
invControllers.controller('DetailCtrl', ['$scope', '$routeParams', 'ItemResource', function ($scope, $routeParams, ItemResource) {
    //Gets all informations of a specific item by id
    var detailData = ItemResource.detailLoad({id: $routeParams.ListItemId}, function () {

        var newAllItems = {
            Id: detailData.id,
            Name: detailData.name,
            State: detailData.state,
            material_id: detailData.type,
            Category: detailData.category ? detailData.category.name : null,
            BuildType: detailData.build_type,
            SalePrice: detailData.sale_price,
            StorageValue: detailData.storage_value,
            Description: detailData.description,
            Type: detailData.type
        };

        $scope.detailData = newAllItems;
    });
}]);

//==============================
//Main-controller
//Used: overall other controller 
//==============================
invControllers.controller('indexCtrl', function ($scope, $location, $anchorScroll, customData) {

    $scope.custom = customData;

    $scope.scrollTo = function () {
        // set the location.hash to null/top
        $location.hash();

        // call $anchorScroll() to use the scroll
        $anchorScroll();
    };
});

