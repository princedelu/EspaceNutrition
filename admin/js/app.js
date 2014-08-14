
angular.module('underscore', []).factory('_', function() {
    return window._;
});

angular.module('EspaceNutrition', ['ngCookies', 'ngRoute','underscore'])
    .config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {

    var access = routingConfig.accessLevels;

    $routeProvider.when('/admin/login',
        {
            templateUrl:    '/admin/partials/login.html',
            controller:     'LoginCtrl',
            access:         access.user
        });
    $routeProvider.when('/admin/dashboard',
        {
            templateUrl:    '/admin/partials/dashboard.html',
            controller:     'LoginCtrl',
            access:         access.user
        });
     $routeProvider.when('/admin/monprofil',
        {
            templateUrl:    '/admin/partials/profil.html',
            controller:     'LoginCtrl',
            access:         access.user
        });
	$routeProvider.when('/admin/utilisateurs',
        {
            templateUrl:    '/admin/partials/utilisateurs.html',
            controller:     'UtilisateurCtrl',
			action : 		'list',
            access:         access.admin
        });
    $routeProvider.when('/admin/404',
        {
            templateUrl:    '/admin/partials/404.html',
            access:         access.public
        });
    $routeProvider.otherwise({redirectTo:'/admin/404'});

    $locationProvider.html5Mode(true);

    $httpProvider.interceptors.push(function($q, $location, $window) {
        return {
			request: function (config) {
			  config.headers = config.headers || {};
			  if ($window.sessionStorage.user) {
				config.headers.Authorization = 'Bearer ' + JSON.parse($window.sessionStorage.user).token;
			  }
			  return config;
			},
            'responseError': function(response) {
                if(response.status === 401 || response.status === 403) {
                    $location.path('/admin/login');
                    return $q.reject(response);
                }
                else {
                    return $q.reject(response);
                }
            }
        };
    });

}])

    .run(['$rootScope', '$location', 'Auth', function ($rootScope, $location, Auth) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.error = null;

            if (!Auth.authorize(next.access)) {
               if(Auth.isLoggedIn()) $location.path('/admin/dashboard');
               else                  $location.path('/admin/login');
            }
        });

    }]);
