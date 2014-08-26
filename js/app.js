
angular.module('underscore', []).factory('_', function() {
    return window._;
});

angular.module('EspaceNutrition', ['ngRoute','underscore'])
    .config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {

    var access = routingConfig.accessLevels;
    
    $routeProvider.when('/',
        {
            templateUrl:    '/partials/public.html',
            controller:     'LoginCtrl',
            access:         access.public,
            css: 			'css/style.css'
        });
    $routeProvider.when('/login',
        {
            templateUrl:    '/partials/login.html',
            controller:     'LoginCtrl',
            access:         access.public,
			css : 			'css/AdminLTE.css'
        });
	$routeProvider.when('/dashboard',
        {
            templateUrl:    '/partials/dashboard.html',
            controller:     'LoginCtrl',
            access:         access.user,
			css : 			'css/AdminLTE.css'

        });
	$routeProvider.when('/utilisateurs',
        {
            templateUrl:    '/partials/utilisateurs.html',
            controller:     'UtilisateurCtrl',
			action : 		'list',
            access:         access.admin,
			css : 			'css/AdminLTE.css'
        });
    $routeProvider.when('/404',
        {
            templateUrl:    '/partials/404.html',
            access:         access.public,
			css : 			'css/AdminLTE.css'
        });
    $routeProvider.otherwise({redirectTo:'/404'});

    $locationProvider.html5Mode(true).hashPrefix('!');

    $httpProvider.interceptors.push(function($q, $location, $window) {
        return {
			request: function (config) {
			  config.headers = config.headers || {};
			  if ($window.sessionStorage.token) {
				config.headers.Authorization = 'Bearer ' + $window.sessionStorage.token;
			  }
			  return config;
			},
            'responseError': function(response) {
                if(response.status === 401) {
                     $window.location.href = '/login';
                    return $q.reject(response);
                }
                else {
                    return $q.reject(response);
                }
            }
        };
    });

}])

    .run(['$rootScope', '$location','$window', 'Auth', function ($rootScope, $location,$window, Auth) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.error = null;
			if (current !== undefined && current.$$route !== undefined)
				$rootScope[current.$$route.originalPath.split('/')[1]] = false;
			if (next !== undefined && next.$$route !== undefined)
				$rootScope[next.$$route.originalPath.split('/')[1]] = true;
            if (!Auth.authorize(next.access)) {
               if(Auth.isLoggedIn()) $location.path('/dashboard');
               else                   $location.path('/login');
            }
        });

    }]);
