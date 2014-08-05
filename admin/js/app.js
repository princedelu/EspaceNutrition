
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
            access:         access.anon
        });
    $routeProvider.when('/admin/home',
        {
            templateUrl:    '/admin/partials/membre.html',
            controller:     'LoginCtrl',
            access:         access.user
        });
    $routeProvider.when('/admin/404',
        {
            templateUrl:    '/admin/partials/404.html',
            access:         access.public
        });
    $routeProvider.otherwise({redirectTo:'/admin/404'});

    $locationProvider.html5Mode(true);

    $httpProvider.interceptors.push(function($q, $location) {
        return {
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
               if(Auth.isLoggedIn()) $location.path('/admin/home');
               else                  $location.path('/admin/login');
            }
        });

    }]);
