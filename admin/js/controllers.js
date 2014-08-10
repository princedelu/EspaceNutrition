(function(){
"use strict";

/* Controllers */
angular.module('EspaceNutrition')
.controller('LoginCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth', function($rootScope, $scope, $location, $route, $window, Auth) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    $scope.rememberme = true;
    $scope.login = function() {
        Auth.login({
                username: $scope.username,
                password: $scope.password,
            },
            function(res) {
                $location.path('/admin/dashboard');
            },
            function(err) {
                $scope.error = err;
            });
    };

    $scope.logout = function() {
        Auth.logout(function() {
            $location.path('/admin/login');
        }, function() {
            $scope.error = "Failed to logout";
        });
    };


}]);
})();
