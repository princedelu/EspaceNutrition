
angular.module('underscore', []).factory('_', function() {
    return window._;
});

$('#pleaseWaitDialog').hide();

angular.module('EspaceNutrition', ['ngRoute','underscore'])
    .config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {

    var access = routingConfig.accessLevels;
    
    $routeProvider.when('/',
        {
            templateUrl:    '/partials/public.php',
            controller:     'EspaceNutritionPublicCtrl',
            access:         access.public
        });
    $routeProvider.when('/login',
        {
            templateUrl:    '/partials/login.html',
            controller:     'EspaceNutritionCtrl',
            action:         'login',
            access:         access.public
        });
	$routeProvider.when('/dashboard',
        {
            templateUrl:    '/partials/admin/dashboard.php',
            controller:     'DashboardCtrl',
            action:         'dashboard',
            access:         access.user

        });
	$routeProvider.when('/utilisateurs',
        {
            templateUrl:    '/partials/admin/utilisateurs.php',
            controller:     'UtilisateurCtrl',
			action : 		'listUtilisateur',
            access:         access.admin
        });
	$routeProvider.when('/paiements',
        {
            templateUrl:    '/partials/admin/paiements.php',
            controller:     'PaiementCtrl',
			action : 		'listPaiement',
            access:         access.admin
        });
    $routeProvider.when('/abonnements',
        {
            templateUrl:    '/partials/admin/abonnements.php',
            controller:     'AbonnementCtrl',
			action : 		'listAbonnement',
            access:         access.admin
        });
    $routeProvider.when('/mesabonnements',
        {
            templateUrl:    '/partials/admin/abonnements.php',
            controller:     'AbonnementCtrl',
			action : 		'listMesAbonnement',
            access:         access.user
        });
    $routeProvider.when('/mesures',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'listMesures',
            access:         access.admin
        });		
    $routeProvider.when('/mesmesures',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'listMesMesures',
            access:         access.user
        });	
    $routeProvider.when('/addpoids',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'addPoids',
            access:         access.user
        });	
    $routeProvider.when('/addrepas',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'addRepas',
            access:         access.user
        });
    $routeProvider.when('/repas/:id',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'modifRepas',
            access:         access.admin
        });
    $routeProvider.when('/monrepas/:id',
        {
            templateUrl:    '/partials/admin/mesures.php',
            controller:     'MesureCtrl',
			action : 		'modifMonRepas',
            access:         access.user
        });
	$routeProvider.when('/articles',
        {
            templateUrl:    '/partials/admin/articles.php',
            controller:     'ArticleCtrl',
			action : 		'listArticles',
            access:         access.admin
        });
    $routeProvider.when('/temoignages',
        {
            templateUrl:    '/partials/admin/temoignages.php',
            controller:     'TemoignageCtrl',
			action : 		'listTemoignages',
            access:         access.admin
        });
    $routeProvider.when('/404',
        {
            templateUrl:    '/partials/404.html',
            access:         access.public
        });
    $routeProvider.otherwise({redirectTo:'/404'});

    $locationProvider.html5Mode(true).hashPrefix('!');

    $httpProvider.interceptors.push(function($q, $location, $window) {
        return {
			request: function (config) {
			  config.headers = config.headers || {};
			  if ($window.sessionStorage.token) {
				config.headers.Authorization = 'Bearer ' + $window.sessionStorage.token;
			  }else{
				var payLoad = {};
				payLoad.iss="http://www.espace-nutrition.fr";
				payLoad.aud="Espace Nutrition";
				payLoad.exp=Math.round(new Date().getTime()/1000)+60;
				payLoad.role="anonyme";

				var jPayLoad = JSON.stringify(payLoad);

				config.headers.Authorization = 'BearerPublic ' + utf8tob64u(jPayLoad);
			  }
			  return config;
			},
            'responseError': function(response) {
                if(response.status === 401) {
                     $location.path('/login');
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
			Auth.adaptCurrentUser();
			if (!Auth.authorize(next.access)) {
				if(Auth.isLoggedIn()) 	$location.path('/dashboard');
				else 					$location.path('/login');
            }
        });

    }]);
