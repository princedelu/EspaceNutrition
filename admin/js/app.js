
angular.module('underscore', []).factory('_', function() {
    return window._;
});

angular.module('Login', ['ngRoute','underscore'])
    .config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {

    var access = routingConfig.accessLevels;

    $routeProvider.when('/admin/login',
        {
            templateUrl:    '/admin/partials/login.html',
            controller:     'LoginCtrl',
            access:         access.user
        });
    
    $routeProvider.otherwise({redirectTo:'/admin/404'});

    $locationProvider.html5Mode(true);

    $httpProvider.interceptors.push(function($q, $location, $window) {
        return {
            'responseError': function(response) {
                if(response.status === 401) {
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
        });

    }]);

angular.module('EspaceNutrition', ['ngRoute','underscore'])
    .config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {

    var access = routingConfig.accessLevels;

    $routeProvider.when('/admin/dashboard',
        {
            templateUrl:    '/admin/partials/dashboard.html',
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
			  if ($window.sessionStorage.token) {
				config.headers.Authorization = 'Bearer ' + $window.sessionStorage.token;
			  }
			  return config;
			},
            'responseError': function(response) {
                if(response.status === 401) {
                     $window.location.href = '/admin/login';
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
			if (current !== undefined)
				$rootScope[current.$$route.originalPath.split('/')[2]] = false;
			if (next !== undefined)
				$rootScope[next.$$route.originalPath.split('/')[2]] = true;
            if (!Auth.authorize(next.access)) {
               if(Auth.isLoggedIn()) $location.path('/admin/dashboard');
               else                   $window.location.href = '/admin/login';
            }
        });

    }]);

	//Enable sidebar toggle
    $("[data-toggle='offcanvas']").click(function(e) {
        e.preventDefault();

        //If window is small enough, enable sidebar push menu
        if ($(window).width() <= 992) {
            $('.row-offcanvas').toggleClass('active');
            $('.left-side').removeClass("collapse-left");
            $(".right-side").removeClass("strech");
            $('.row-offcanvas').toggleClass("relative");
        } else {
            //Else, enable content streching
            $('.left-side').toggleClass("collapse-left");
            $(".right-side").toggleClass("strech");
        }
    });
