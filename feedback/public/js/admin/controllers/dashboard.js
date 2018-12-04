var dashboardApp = angular.module('dashboardApp',['ngSanitize']);


dashboardApp.controller('dashboardAppController',function($scope,$rootScope,$http){
    console.log("Dashboard Requests Controller ");

    $scope.requestPath = "/admin/dashboard/";
    $rootScope.admin = {};
    $scope.viewName ={
        'name':"",
        'surveys':'',


    };




    $scope.view = function(view){
      $scope.viewName = view;


    };

    $scope.getAdmin = function(){
        $http.get('/admin/dashboard/getAdmin').then(function (response) {

          $rootScope.admin= response.data;
           // console.log($rootScope.admin);
        });
    };

    $scope.getAdmin();
});





var dashboardAppEl = angular.element($("#dashboardApp"));
angular.element(document).ready(function() {

    angular.bootstrap(dashboardAppEl, ['dashboardApp']);

});
