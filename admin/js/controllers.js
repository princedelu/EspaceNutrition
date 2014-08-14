(function(){
"use strict";

/* Controllers */
angular.module('EspaceNutrition')
.controller('LoginCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth', function($rootScope, $scope, $location, $route, $window, Auth) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    $scope.login = function() {
        Auth.login({
                username: $scope.username,
                password: $scope.password,
            },
            function(res) {
				$window.sessionStorage.token = res;
                $location.path('/admin/dashboard');
            },
            function(err) {
                $scope.error = err;
            });
    };

    $scope.logout = function() {
        Auth.logout(function() {
			delete $window.sessionStorage.token;
            $location.path('/admin/login');
        }, function(err) {
            $scope.error = err;
        });
    };


}]);

angular.module('EspaceNutrition')
.controller('UtilisateurCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','UtilisateurFactory', function($rootScope, $scope, $location, $route, $window, Auth,UtilisateurFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

	var action = $route.current.action;

	$scope.list = function () {
		$scope.success = '';
		$scope.error = '';
		$scope.loading = true;
		UtilisateurFactory.list( 
			function (res) {
				$scope.utilisateurs = res;
				$scope.loading = false;
			},
			function (err) {
				$scope.error = "Impossible de recuperer les utilisateurs";
				$scope.loading = false;
			}
		);
	};

	switch (action) {
		case 'list':
			$scope.list();
			break;
		case 'get':
			var id = $routeParams.id;
			$scope.loadListe();
			$scope.get(id);
			break;
		case 'add':
			$scope.loadListe();
			break;
		default:
		break;
	}
}]);

})();
