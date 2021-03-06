
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
(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('AbonnementCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','Auth', 'AbonnementFactory','UtilisateurFactory', function($rootScope, $scope, $location, $route, $window, Auth,AbonnementFactory,UtilisateurFactory) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.listAbonnement = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;
        AbonnementFactory.list( 
            function (res) {
                $scope.loading = false;
                var data = $.map(res, function(el, i) {
                  return [[el.ID,el.EMAIL, el.DATEDEBUT, el.DATEFIN, el.TYPE, el.ACTIF,""]];
                });
                var table = $("#abonnements").dataTable({
                    "aaData": data,
                    "aoColumns": [
                        { "sTitle": "Id" },
                        { "sTitle": "Email" },
                        { "sTitle": "Date de début" },
                        { "sTitle": "Date de fin" },
                        { "sTitle": "Type" },
                        { "sTitle": "Actif" },
                        { "sTitle": "Action" }
                    ],
                    "oLanguage": {
                          "sSearch": "Recherche:",
                        "sZeroRecords": "Pas d'éléménts à afficher",
                        "sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
                        "sInfoEmpty": "Pas d'éléments",
                        "sInfoFiltered": " - filtrés sur _MAX_ éléments",
                        "sLengthMenu": "Afficher _MENU_ éléments",                    
                        "oPaginate": {
                            "sFirst": "Premier",
                            "sLast" : "Dernier",
                            "sNext" : "Suivant",
                            "sPrevious" : "Précédent"
                          }
                    },
                    "aoColumnDefs": [   
                        { 
							"targets": 0, 
							"visible" : false,
                			"searchable": false
						},
						{ 
							"targets": 5, 
							"sType": "html", 
							"render": function(data, type, row) {
								var spanOuv="&lt;span class=&quot;label ";
								var label = "";
								switch(data) {
									case false:
										label="label-info&quot;&gt;Non actif";
										break;
									case true:
										label="label-success&quot;&gt;Actif";
										break;
									default:
										label="label-danger&quot;&gt;???";
								} 
									
								var spanFerm = "&lt;/span&gt;";
								var result = spanOuv.concat(label).concat(spanFerm);
								return $("<div/>").html(result).text();
							} 
						},
						{ 
							"targets": 6, 
							"sType": "html", 
							"render": function(data, type, row) {
								var result = "";
								var resultTmp = "";
								var id = "";
								var fin = "";
								// Suppression
								resultTmp = "&lt;button class=&quot;btn btn-link app-btn-delete&quot; ng-click=&quot;delete(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-times&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = result.concat(resultTmp).concat(id).concat(fin);	
								return $("<div/>").html(result).text();
							} 
                        }
                    ],
                    "order": [[ 1, "desc" ]]
                    
                });
                $('#abonnements tbody').on( 'click', 'button', function () {
					var name = this.attributes[1].nodeName;
					var value = this.attributes[1].nodeValue;
					var tab = "";
					var result = "";
					if(name == "ng-click" && value.indexOf("delete") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.supprimer(result);
					}
				} );
            },
            function (err) {
                $scope.error = "Impossible de recuperer les abonnements";
                $scope.loading = false;
            }
        );
    };

    $scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cet abonnement?");
        if (retVal === true) {
		    AbonnementFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
		            $route.reload();
		        },
		        function (err) {
		            $scope.error = err;
		            $route.reload();
		        });
		}
    };

    $scope.listMesAbonnement = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;
        AbonnementFactory.listMine( 
            function (res) {
                $scope.loading = false;
                var data = $.map(res, function(el, i) {
                  return [[el.ID,el.EMAIL, el.DATEDEBUT, el.DATEFIN, el.TYPE, el.ACTIF]];
                });
                var table = $("#abonnements").dataTable({
                    "aaData": data,
                    "aoColumns": [
                        { "sTitle": "Id" },
                        { "sTitle": "Email" },
                        { "sTitle": "Date de début" },
                        { "sTitle": "Date de fin" },
                        { "sTitle": "Type" },
                        { "sTitle": "Actif" }
                    ],
                    "oLanguage": {
                          "sSearch": "Recherche:",
                        "sZeroRecords": "Pas d'éléménts à afficher",
                        "sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
                        "sInfoEmpty": "Pas d'éléments",
                        "sInfoFiltered": " - filtrés sur _MAX_ éléments",
                        "sLengthMenu": "Afficher _MENU_ éléments",                    
                        "oPaginate": {
                            "sFirst": "Premier",
                            "sLast" : "Dernier",
                            "sNext" : "Suivant",
                            "sPrevious" : "Précédent"
                          }
                    },
                    "aoColumnDefs": [   
                        { 
							"targets": 0, 
							"visible" : false,
                			"searchable": false
						},
						{ 
							"targets": 5, 
							"sType": "html", 
							"render": function(data, type, row) {
								var spanOuv="&lt;span class=&quot;label ";
								var label = "";
								switch(data) {
									case false:
										label="label-info&quot;&gt;Non actif";
										break;
									case true:
										label="label-success&quot;&gt;Actif";
										break;
									default:
										label="label-danger&quot;&gt;???";
								} 
									
								var spanFerm = "&lt;/span&gt;";
								var result = spanOuv.concat(label).concat(spanFerm);
								return $("<div/>").html(result).text();
							} 
						}
                    ],
                    "order": [[ 1, "desc" ]]
                    
                });
            },
            function (err) {
                $scope.error = "Impossible de recuperer les abonnements";
                $scope.loading = false;
            }
        );
    };

    $scope.createLoad = function (id) {
        $scope.success = '';
        $scope.error = '';

		$scope.email = "";
        $scope.datedebut = "";
		$scope.datefin = "";
		$scope.id = "";

        UtilisateurFactory.list(
	        function (res) {
	            $scope.success = 'Succes';
                var result = _.filter(res, function(user) {
                  return user.role <  2;
                });

                $scope.users = result;
	        },
	        function (err) {
	            $scope.error = err;	            
	        }
        );

		$('#datedebut').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.datedebut = e.currentTarget.value;
        });
        $('#datefin').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.datefin = e.currentTarget.value;
        });
		$('#bs-abonnement').modal('show');
		
    };

    $scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        $scope.errorDate = 'false';
        $scope.pbuser = 'false';
        var dateDebutTab=$scope.datedebut.split("-");
        var dateDebutOrder = dateDebutTab[2]+dateDebutTab[1]+dateDebutTab[0];
        var dateFinTab=$scope.datefin.split("-");
        var dateFinOrder = dateFinTab[2]+dateFinTab[1]+dateFinTab[0];
        if (dateFinOrder<dateDebutOrder){
            $scope.errorDate = 'true';
        }else{
            var objetValue = {};
		    objetValue.email=$scope.email.email;
		    objetValue.datedebut=$scope.datedebut;
		    objetValue.datefin=$scope.datefin;
		    objetValue.type=$scope.type;

		    if ($scope.id === ""){
			    AbonnementFactory.put(objetValue,
				    function () {
				        $scope.success = 'Succes';
					    $('#bs-abonnement').on('hidden.bs.modal', function (e) {
					      $route.reload();
					    });
					    $('#bs-abonnement').modal('hide');
				    },
				    function (err) {
				        $scope.error = err;
				        if (err == 'Doublon') {
				            $scope.doublon = 'true';
				        }
                        if (err == 'PbUser') {
				            $scope.pbuser = 'true';
				        }
				    });
		    }else{
			    objetValue.id=$scope.id;
			    AbonnementFactory.post(objetValue,
				    function () {
				        $scope.success = 'Succes';
					    $('#bs-abonnement').on('hidden.bs.modal', function (e) {
					      $route.reload();
					    });
					    $('#bs-abonnement').modal('hide');
				    },
				    function (err) {
				        $scope.error = err;
				        if (err == 'Doublon') {
				            $scope.doublon = 'true';
				        }
                        if (err == 'PbUser') {
				            $scope.pbuser = 'true';
				        }
				    });
		    }
        }
		
    };


    switch (action) {
        case 'listAbonnement':
            $scope.listAbonnement();
        break;
        case 'listMesAbonnement':
            $scope.listMesAbonnement();
        break;
        default:
        break;
    }
    
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition').factory('AbonnementFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/abonnements').success(success).error(error);
        },
        listMine: function(success, error) {
			$http.get('/api/mesabonnements').success(success).error(error);
		},
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/abonnement/' + id
			}).success(success).error(error);			
		},
        put: function(objet, success, error) {
			$http.put('/api/abonnement', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/abonnement', objet).success(success).error(error);
		}
    };
}]);

})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('ArticleCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','Auth', 'ArticleFactory','UtilisateurFactory', function($rootScope, $scope, $location, $route, $window, Auth,ArticleFactory,UtilisateurFactory) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.listArticles = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;
        ArticleFactory.list( 
            function (res) {
                $scope.loading = false;
				
                var data = $.map(res.result, function(el, i) {
					var categories_libelle = "";
					_.each(el.categories,function(categorie){
						categories_libelle = categories_libelle + categorie.libelle_long + "<br/>";
					});
					categories_libelle=categories_libelle.substring(0,categories_libelle.length-5);
					return [[el.id,el.titre, el.auteur, el.date,categories_libelle,""]];
                });
                var table = $("#articles").dataTable({
                    "aaData": data,
                    "aoColumns": [
                        { "sTitle": "Id" },
                        { "sTitle": "Titre" },
                        { "sTitle": "Auteur" },
                        { "sTitle": "Date " },
                        { "sTitle": "Catégorie" },
                        { "sTitle": "Action" }
                    ],
                    "oLanguage": {
                        "sSearch": "Recherche:",
                        "sZeroRecords": "Pas d'éléménts à afficher",
                        "sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
                        "sInfoEmpty": "Pas d'éléments",
                        "sInfoFiltered": " - filtrés sur _MAX_ éléments",
                        "sLengthMenu": "Afficher _MENU_ éléments",                    
                        "oPaginate": {
                            "sFirst": "Premier",
                            "sLast" : "Dernier",
                            "sNext" : "Suivant",
                            "sPrevious" : "Précédent"
                          }
                    },
                    "aoColumnDefs": [   
                        { 
							"targets": 5, 
							"sType": "html", 
							"render": function(data, type, row) {
								var result = "";
								var resultTmp = "";
								var id = "";
								var fin = "";
								// Modification
								resultTmp = "&lt;button class=&quot;btn btn-link&quot; ng-click=&quot;update(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-pencil&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = resultTmp.concat(id).concat(fin);
								// Suppression
								resultTmp = "&lt;button class=&quot;btn btn-link app-btn-delete&quot; ng-click=&quot;delete(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-times&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = result.concat(resultTmp).concat(id).concat(fin);	
								return $("<div/>").html(result).text();
							} 
                        }
                    ],
                    "order": [[ 0, "desc" ]]
                    
                });
                $('#articles tbody').on( 'click', 'button', function () {
					var name = this.attributes[1].nodeName;
					var value = this.attributes[1].nodeValue;
					var tab = "";
					var result = "";
					if(name == "ng-click" && value.indexOf("delete") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.supprimer(result);
					}
					if(name == "ng-click" && value.indexOf("update") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.updateLoad(result);
					}
				} );
            },
            function (err) {
                $scope.error = "Impossible de recuperer les articles";
                $scope.loading = false;
            }
        );
		ArticleFactory.listCategories(
			function (res1) {
				$scope.categories = res1;
				$scope.success = 'Succes';
				$('#dateArticle').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
					$scope.date = e.currentTarget.value;
				}); 
			},
			function (err) {
			$scope.error = err;
		});
    };
	
	$scope.updateLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		ArticleFactory.get(id,
			function (res) {
				$scope.id = res.id;
				$scope.titre = res.titre;
				$scope.auteur = res.auteur;
				$scope.partie1 = res.partie1;
				$scope.partie2 = res.partie2;
				$scope.date = res.date;
				
				var availablesOptions = [];
				var categorieOptions = {};
				_.each($scope.categories,function(categorieOptions){
					availablesOptions.push({value:categorieOptions.id,name:categorieOptions.libelle_long});
				});
				var selectedsOptions = [];
				_.each(res.categories,function(categorieOptions){
					selectedsOptions.push({value:categorieOptions.id,name:categorieOptions.libelle_long});
				});
				$scope.categories_options = {
					availableOptions: availablesOptions,
					selectedOption: selectedsOptions //This sets the default value of the select in the ui
				};
				$scope.formArticle = true;
			},
			function (err) {
				$scope.error = err;
			});
		
    };

	$scope.createLoad = function (id) {
        $scope.success = '';
        $scope.error = '';

		$scope.id = "";
		$scope.titre = "";
		$scope.auteur = "";
		$scope.partie1 = "";
		$scope.partie2 = "";
		$scope.date = "";
		
		var availablesOptions = [];
		var categorieOptions = {};
		_.each($scope.categories,function(categorieOptions){
			availablesOptions.push({value:categorieOptions.id,name:categorieOptions.libelle_long});
		});
		var selectedsOptions = [];
		
		$scope.categories_options = {
			availableOptions: availablesOptions,
			selectedOption: selectedsOptions //This sets the default value of the select in the ui
		};

		$scope.formArticle = true;
		
    };
	
	$scope.createClose = function (id) {
        
		$scope.formArticle = false;
		
    };
	
	$scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        var objetValue = {};
		objetValue.titre=$scope.titre;
		objetValue.auteur=$scope.auteur;
		objetValue.partie1=$scope.partie1;
		objetValue.partie2=$scope.partie2;
		objetValue.date=$scope.date;
		objetValue.categories=$scope.categories_options.selectedOption;

		if ($scope.id === ""){
			ArticleFactory.put(objetValue,
				function () {
				    $scope.success = 'Succes';
					$route.reload();
					$scope.formArticle = false;
				},
				function (err) {
				    $scope.error = err;
				});
		}else{
			objetValue.id=$scope.id;
			ArticleFactory.post(objetValue,
				function () {
				    $scope.success = 'Succes';
					$route.reload();
					$scope.formArticle = false;
				},
				function (err) {
				    $scope.error = err;
				    
				});
		}
		
    };

    $scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cet article?");
        if (retVal === true) {
		    ArticleFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
		            $route.reload();
		        },
		        function (err) {
		            $scope.error = err;
		            $route.reload();
		        });
		}
    };

    

    

    


    switch (action) {
        case 'listArticles':
            $scope.listArticles();
        break;
        default:
        break;
    }
    
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition')
  .value('uiTinymceConfig', {})
  .directive('uiTinymce', ['$rootScope', '$compile', '$timeout', '$window', '$sce', 'uiTinymceConfig', function($rootScope, $compile, $timeout, $window, $sce, uiTinymceConfig) {
    uiTinymceConfig = uiTinymceConfig || {};
    var generatedIds = 0;
    var ID_ATTR = 'ui-tinymce';
    if (uiTinymceConfig.baseUrl) {
      tinymce.baseURL = uiTinymceConfig.baseUrl;
    }

    return {
      require: ['ngModel', '^?form'],
      priority: 999,
      link: function(scope, element, attrs, ctrls) {
        if (!$window.tinymce) {
          return;
        }

        var ngModel = ctrls[0],
          form = ctrls[1] || null;

        var expression, options = {}, tinyInstance,
          updateView = function(editor) {
            var content = editor.getContent({format: options.format}).trim();
            content = $sce.trustAsHtml(content);

            ngModel.$setViewValue(content);
            if (!$rootScope.$$phase) {
              scope.$digest();
            }
          };

        function toggleDisable(disabled) {
          if (disabled) {
            ensureInstance();

            if (tinyInstance) {
              tinyInstance.getBody().setAttribute('contenteditable', false);
            }
          } else {
            ensureInstance();

            if (tinyInstance && !tinyInstance.settings.readonly) {
              tinyInstance.getBody().setAttribute('contenteditable', true);
            }
          }
        }

        // generate an ID
        attrs.$set('id', ID_ATTR + '-' + generatedIds++);

        expression = {};

        angular.extend(expression, scope.$eval(attrs.uiTinymce));

        //Debounce update and save action
        var debouncedUpdate = (function(debouncedUpdateDelay) {
          var debouncedUpdateTimer;
          return function(ed) {
	        $timeout.cancel(debouncedUpdateTimer);
	         debouncedUpdateTimer = $timeout(function() {
              return (function(ed) {
                ed.save();
                updateView(ed);
              })(ed);
            }, debouncedUpdateDelay);
          };
        })(400);
		
		var plugins = {plugins : 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor colorpicker textpattern imagetools'};

		var toolbar1= { toolbar1 : 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'};
		var toolbar2= { toolbar2 : 'forecolor backcolor emoticons'};
		
        var setupOptions = {
          // Update model when calling setContent
          // (such as from the source editor popup)
          setup: function(ed) {
            ed.on('init', function() {
              ngModel.$render();
              ngModel.$setPristine();
              //ngModel.$setUntouched();
              if (form) {
                form.$setPristine();
              }
            });

            // Update model when:
            // - a button has been clicked [ExecCommand]
            // - the editor content has been modified [change]
            // - the node has changed [NodeChange]
            // - an object has been resized (table, image) [ObjectResized]
            ed.on('ExecCommand change NodeChange ObjectResized', function() {
              debouncedUpdate(ed);
            });

            ed.on('blur', function() {
              element[0].blur();
              ngModel.$setTouched();
              scope.$digest();
            });

            ed.on('remove', function() {
              element.remove();
            });

            if (expression.setup) {
              expression.setup(ed, {
                updateView: updateView
              });
            }
          },
          format: expression.format || 'html',
          selector: '#' + attrs.id
        };
        // extend options with initial uiTinymceConfig and
        // options from directive attribute value
        angular.extend(options, uiTinymceConfig, expression, setupOptions,plugins,toolbar1,toolbar2);
        // Wrapped in $timeout due to $tinymce:refresh implementation, requires
        // element to be present in DOM before instantiating editor when
        // re-rendering directive
        $timeout(function() {
          if (options.baseURL){
            tinymce.baseURL = options.baseURL;
          }
          tinymce.init(options);
          toggleDisable(scope.$eval(attrs.ngDisabled));
        });

        ngModel.$formatters.unshift(function(modelValue) {
          return modelValue ? $sce.trustAsHtml(modelValue) : '';
        });

        ngModel.$parsers.unshift(function(viewValue) {
          return viewValue ? $sce.getTrustedHtml(viewValue) : '';
        });

        ngModel.$render = function() {
          ensureInstance();

          var viewValue = ngModel.$viewValue ?
            $sce.getTrustedHtml(ngModel.$viewValue) : '';

          // instance.getDoc() check is a guard against null value
          // when destruction & recreation of instances happen
          if (tinyInstance &&
            tinyInstance.getDoc()
          ) {
            tinyInstance.setContent(viewValue);
            // Triggering change event due to TinyMCE not firing event &
            // becoming out of sync for change callbacks
            tinyInstance.fire('change');
          }
        };

        attrs.$observe('disabled', toggleDisable);

        // This block is because of TinyMCE not playing well with removal and
        // recreation of instances, requiring instances to have different
        // selectors in order to render new instances properly
        scope.$on('$tinymce:refresh', function(e, id) {
          var eid = attrs.id;
          if (angular.isUndefined(id) || id === eid) {
            var parentElement = element.parent();
            var clonedElement = element.clone();
            clonedElement.removeAttr('id');
            clonedElement.removeAttr('style');
            clonedElement.removeAttr('aria-hidden');
            tinymce.execCommand('mceRemoveEditor', false, eid);
            parentElement.append($compile(clonedElement)(scope));
          }
        });

        scope.$on('$destroy', function() {
          ensureInstance();

          if (tinyInstance) {
            tinyInstance.remove();
            tinyInstance = null;
          }
        });

        function ensureInstance() {
          if (!tinyInstance) {
            tinyInstance = tinymce.get(attrs.id);
          }
        }
      }
    };
  }]);
  
  
  })();(function(){
"use strict";

angular.module('EspaceNutrition').factory('ArticleFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/articles').success(success).error(error);
        },
		listCategories: function(success, error) {
            $http.get('/api/categories').success(success).error(error);
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/articles/' + id
			}).success(success).error(error);			
		},
		get: function(id, success, error) {
			$http.get('/api/articles/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/articles', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/articles', objet).success(success).error(error);
		}
    };
}]);

})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','UtilisateurFactory','MesureFactory', function($rootScope, $scope, $location, $route, $window, Auth,UtilisateurFactory,MesureFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

	$scope.role = "1";

	if ($location.search().token !== undefined)
	{
		$scope.token = $location.search().token;
	}else{
		$scope.confirmpassword = "value";
	}

    $scope.logout = function() {
		delete $window.sessionStorage.token;
		 $location.path('/login');
    };
    
    $scope.login = function() {
		if ($scope.token === undefined){
		    Auth.login({
		            email: $scope.email,
		            password: $scope.password,
		        },
		        function(res) {
					$window.sessionStorage.token = res;
		            $location.path('/dashboard');
		        },
		        function(err) {
		            $scope.error = err;
		        });
		}else{
			if ($scope.password != $scope.confirmpassword){
				$scope.error = 'Les mots de passe ne sont pas identiques';
			}else{
				Auth.modificationPassword({
		            email: $scope.email,
		            password: $scope.password,
					token : $scope.token
		        },
		        function(res) {
					$window.sessionStorage.token = res;
		            $location.path('/dashboard');
		        },
		        function(err) {
		            $scope.error = err;
		        });
			}
		}
    };

	$scope.go = function ( path ) {
        $window.location.href = path;
    };

	$scope.demandeChangementPassword = function () {
        $scope.success = "";
        $scope.error = "";
        if ($scope.email === undefined){
            $scope.error = 'Veuillez saisir votre email';
        }else{
            Auth.sendMailToken({
                email: $scope.email
                },
                function(res) {
                    $scope.success = 'Un mail a été envoyé à l adresse ci-dessous afin de modifier votre mot de passe';
                },
                function(err) {
                    $scope.error = err;
                });
        }
    };

	$scope.monprofilLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		Auth.get(
		        function (res) {
					$scope.email = res.EMAIL;
					$scope.nom = res.NOM;
					$scope.prenom = res.PRENOM;
					$scope.datenaissance = res.DATENAISSANCE;
					$scope.id = res.ID;
		            $scope.success = 'Succes';
					$('#dateNaissanceProfil').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
                        $scope.datenaissance = e.currentTarget.value;
                    });
		            $('#bs-profil').modal('show');
		        },
		        function (err) {
		            $scope.error = err;
		        });
    };

	$scope.updateProfil = function () {
        $scope.success = '';
        $scope.error = '';

		if ($scope.password != $scope.passwordConfirm){
			$scope.error = 'Les mots de passe ne sont pas identiques';
		}else{

		    var objetValue = {};
			objetValue.email=$scope.email;
			objetValue.nom=$scope.nom;
			objetValue.password=$scope.password;
			objetValue.prenom=$scope.prenom;
			objetValue.datenaissance=$scope.datenaissance;
			objetValue.id=$scope.id;
			objetValue.profil=1;

			Auth.post(objetValue,
				function () {
					$scope.success = 'Succes';
					$('#bs-profil').modal('hide');
				},
				function (err) {
					$scope.error = err;
				});
		}
    };

	$scope.offcanvas = function (id) {
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
	};

    $scope.getDataHeader = function () {
        if ($scope.user.role != $scope.userRoles.admin){
            $scope.success = '';
            $scope.error = '';

		    MesureFactory.listNotificationsUser(
			    function (res) {
				    $scope.success = 'Succes';
				    $scope.notifications = res;
			    },
			    function (err) {
				    $scope.error = err;
			    });
        }
    };


    switch (action) {
        case 'login':
        break;
        default:
            $scope.getDataHeader();
        break;
    }

}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition')
.directive('accessLevel', ['Auth', function(Auth) {
    return {
        restrict: 'A',
        link: function($scope, element, attrs) {
            var prevDisp = element.css('display');
            var userRole;
            var accessLevel;

            $scope.user = Auth.user;
            $scope.$watch('user', function(user) {
                if(user.role)
                    userRole = user.role;
                updateCSS();
            }, true);

            attrs.$observe('accessLevel', function(al) {
                if(al) accessLevel = $scope.$eval(al);
                updateCSS();
            });

            function updateCSS() {
                if(userRole && accessLevel) {
                    if(!Auth.authorize(accessLevel, userRole)){
                        element.css('display', 'none');
                    }
                    else{
                        element.css('display', prevDisp);
                    }
                }
            }
        }
    };
}]);

angular.module('EspaceNutrition').directive('activeNav', ['$location', function($location) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var nestedA = element.find('a')[0];
            var path = nestedA.href;

            scope.location = $location;
            scope.$watch('location.absUrl()', function(newPath) {
                if (path === newPath) {
                    element.addClass('active');
                } else {
                    element.removeClass('active');
                }
            });
        }

    };

}]);

var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
angular.module('EspaceNutrition').directive('smartFloat', function() {
  return {
    require: 'ngModel',
    link: function(scope, elm, attrs, ctrl) {
      ctrl.$parsers.unshift(function(viewValue) {
        if (FLOAT_REGEXP.test(viewValue)) {
          ctrl.$setValidity('float', true);
          return parseFloat(viewValue.replace(',', '.'));
        } else {
          ctrl.$setValidity('float', false);
          return undefined;
        }
      });
    }
  };
});


angular.module('EspaceNutrition').directive('d3CourbePoids', ['$rootScope', '$location','$window', function($rootScope, $location,$window) {
	return {
		restrict: 'E',
		replace : false,
		scope: {data: '=data'},
		link: function(scope, element, attrs) {
			var window = angular.element($window);
			var parent = angular.element(element.parent());
			var w = getParentWidth();
			var h = 450;
			
			var nbTickDateMax = Math.round(w / 120);

			var monthNames = [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
				"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ];

			var maxDataPointsForDots = 50,
				transitionDuration = 1000;

            var margin = 40;
        
        	var t = null;

            scope.render = function(data) {
                
                var svg = null,
				yAxisGroup = null,
				yAxisGroup1 = null,
				xAxisGroup = null,
				dataCirclesGroup = null,
				dataLinesGroup = null;

                if (data!==undefined){
                    d3.select("svg").remove();
                    svg = d3.select(element[0]).select('svg').select('g');
			        if (svg.empty()) {
				        svg = d3.select(element[0])
					        .append('svg:svg')
						        .attr('width', w)
						        .attr('height', h)
						        .attr('class', 'viz')
					        .append('svg:g')
						        .attr('transform', 'translate(' + margin + ',' + margin + ')');
			        }

                    var min = d3.min(data, function(d) { return parseFloat(d.value); });
                    min = min - 10;

			        var pointRadius = 4;

			        var x = d3.time.scale().range([0, w - margin * 2]).domain([data[0].date, data[data.length - 1].date]);

                    var max = d3.max(data, function(d) { return parseFloat(d.value); });
                    max = max + 10;

			        var y = d3.scale.linear().range([h - margin * 2, 0]).domain([min, max]);

                    var xAxis = d3.svg.axis().scale(x).tickSize(h - margin * 2).tickPadding(20).ticks(nbTickDateMax).tickFormat(d3.time.format("%d/%m/%Y"));
			        var yAxis = d3.svg.axis().scale(y).orient('left').tickSize(-w + margin * 2).tickPadding(10).ticks(10);

			        // y ticks and labels
			        yAxisGroup = svg.append('svg:g')
				        .attr('class', 'yTick')
				        .call(yAxis);

			        svg.append("text")
				        .attr("transform", "rotate(-90)")
				        .attr("y", 0 - margin)
				        .attr("x",40 - (h / 2))
				        .attr("dy", "1em")
				        .style("text-anchor", "middle")
				        .attr('class', 'textPoids')
				        .text("Poids");
			
			        xAxisGroup = svg.append('svg:g')
				        .attr('class', 'xTick')
				        .call(xAxis);
                    
			        // Draw the lines
			        dataLinesGroup = svg.append('svg:g');

			        var dataLines = dataLinesGroup.selectAll('.data-line')
					        .data([data]);

                    var line = d3.svg.line()
				        // assign the X function to plot our line as we wish
				        .x(function(d,i) { 
					        return x(d.date); 
				        })
				        .y(function(d) { 
					        return y(d.value); 
				        })
				        .interpolate("linear");


			        dataLines.enter().append('path')
				         .attr('class', 'data-line')
				         .style('opacity', 0.3)
				         .attr("d", line(data));


			        // Draw the points
			        dataCirclesGroup = svg.append('svg:g');

			        var circles = dataCirclesGroup.selectAll('.data-point')
				        .data(data);

			        circles
				        .enter()
					        .append('svg:circle')
						        .attr('class', 'data-point')
						        .style('opacity', 1)
						        .attr('cx', function(d) { return x(d.date); })
						        .attr('cy', function(d) { return y(d.value); })
						        .attr('r', function() { return (data.length <= maxDataPointsForDots) ? pointRadius : 0; });

			          $('svg circle').tipsy({ 
				        gravity: 'w', 
				        html: true, 
				        title: function() {
				          	var d = this.__data__;
				          	var pDate = d.date;
					        return 'Date : ' + pDate.getDate() + " " + monthNames[pDate.getMonth()] + " " + pDate.getFullYear() + '<br>Valeur : ' + d.value; 
				        }
			        });
                }
            };

            //Watch 'data' and run scope.render(newVal) whenever it changes
            //Use true for 'objectEquality' property so comparisons are done on equality and not reference
            scope.$watch('data', function(newValue, oldValue){
                scope.render(scope.data);
            }, true);  

			function returnDigit(val) { 
				var re = /\d+/;
				var digit = val.match(re)[0];
				return digit;
			} 
			
			function getParentWidth() {
                var widthParent;
                if (returnDigit(parent.css('width')) == 100){
                    widthParent=838;
                }else{
                    widthParent=returnDigit(parent.css('width'));
                }
                
				return widthParent - returnDigit(parent.css('padding-left')) - returnDigit(parent.css('padding-right'));
			}
		}

	};
}]);

})();
(function(){
"use strict";
/*!
 * Start Bootstrap - Freelancer Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('.page-scroll a').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Floating label headings for the contact form
$(function() {
    $("body").on("input propertychange", ".floating-label-form-group", function(e) {
        $(this).toggleClass("floating-label-form-group-with-value", !! $(e.target).val());
    }).on("focus", ".floating-label-form-group", function() {
        $(this).addClass("floating-label-form-group-with-focus");
    }).on("blur", ".floating-label-form-group", function() {
        $(this).removeClass("floating-label-form-group-with-focus");
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
});

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});

})();

(function(exports){

    var config = {

        /* List all the roles you wish to use in the app
        * You have a max of 31 before the bit shift pushes the accompanying integer out of
        * the memory footprint for an integer
        */
        roles :[
            'public',
            'user',
            'admin'],

        /*
        Build out all the access levels you want referencing the roles listed above
        You can use the "*" symbol to represent access to all roles
         */
        accessLevels : {
            'public' : "*",
            'anon': ['public'],
            'userOnly' : ['user'],
            'user' : ['user', 'admin'],
            'admin': ['admin']
        }

    };

    exports.userRoles = buildRoles(config.roles);
    exports.accessLevels = buildAccessLevels(config.accessLevels, exports.userRoles);
	exports.publicKey=""+
"-----BEGIN PUBLIC KEY-----\n"+
"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAj0uWKOlMXWpht6wvASYs\n"+
"esg+Dl/DuVlHFS/wX+FHS5EmFPh2MB+IsJSlyfk21Ozylpzq10U0omP6ADAMqDmK\n"+
"5iTTiyvAJkZJBtgpRPaujMtDctjV1O0ViWOe6+uphKRWdg/aMWtPBJXMnw/rDCCp\n"+
"OQGdkhAkWjUdsHnABZ38EiFlb0PJhp+jgyhrSgYldn8qBDB1X/YRRfy4QyTgmX6O\n"+
"/fu9Lj8mabZn4K7HNOSDjljTiUycG7VM5hLRKt3CKN/c50JTlJ9wzxLMbW1itm/U\n"+
"VuvQtvO1611vF9Vuusjy2qKum0IZI80eJbKR19916KBsUXoJnSitMH7cW+K7btiq\n"+
"5wIDAQAB\n"+
"-----END PUBLIC KEY-----";


    /*
        Method to build a distinct bit mask for each role
        It starts off with "1" and shifts the bit to the left for each element in the
        roles array parameter
     */

    function buildRoles(roles){

        var bitMask = "01";
        var userRoles = {};

        for(var role in roles){
            var intCode = parseInt(bitMask, 2);
            userRoles[roles[role]] = {
                bitMask: intCode,
                title: roles[role]
            };
            bitMask = (intCode << 1 ).toString(2);
        }

        return userRoles;
    }

    /*
    This method builds access level bit masks based on the accessLevelDeclaration parameter which must
    contain an array for each access level containing the allowed user roles.
     */
    function buildAccessLevels(accessLevelDeclarations, userRoles){

        var accessLevels = {};
        for(var level in accessLevelDeclarations){

            var resultBitMask = '';
            var role;
            if(typeof accessLevelDeclarations[level] == 'string'){
                if(accessLevelDeclarations[level] == '*'){

                    for( role in userRoles){
                        resultBitMask += "1";
                    }
                    //accessLevels[level] = parseInt(resultBitMask, 2);
                    accessLevels[level] = {
                        bitMask: parseInt(resultBitMask, 2),
                        title: accessLevelDeclarations[level]
                    };
                }
                else console.log("Access Control Error: Could not parse '" + accessLevelDeclarations[level] + "' as access definition for level '" + level + "'");

            }
            else {

                resultBitMask = 0;
                for(role in accessLevelDeclarations[level]){
                    if(userRoles.hasOwnProperty(accessLevelDeclarations[level][role]))
                        resultBitMask = resultBitMask | userRoles[accessLevelDeclarations[level][role]].bitMask;
                    else console.log("Access Control Error: Could not find role '" + accessLevelDeclarations[level][role] + "' in registered roles while building access for '" + level + "'");
                }
                accessLevels[level] = {
                    bitMask: resultBitMask,
                    title: accessLevelDeclarations[level][role]
                };
            }
        }

        return accessLevels;
    }

})(typeof exports === 'undefined' ? this.routingConfig = {} : exports);


(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('Auth', ['$http','$window', function($http,$window){

    var accessLevels = routingConfig.accessLevels;
    var userRoles = routingConfig.userRoles;
	var publicKey = routingConfig.publicKey;

    var currentUser = adaptUser($window.sessionStorage.token);
	var role = userRoles.public;

	function changeUser(user) {
		_.extend(currentUser, user);
	}

	function verifyToken(token){

		var isValid = false;
		try {
			isValid = KJUR.jws.JWS.verify(token, publicKey);
			var pClaim = readToken(token);
			if (pClaim.exp < Math.round(new Date().getTime()/1000)){
				isValid = false;
			}
		} catch (ex) {
			isValid = false;
		} 

		return isValid;
	}

	function readToken(token){

		var a = token.split(".");
		var uClaim = b64utos(a[1]);
		return KJUR.jws.JWS.readSafeJSONString(uClaim);
	}

	function adaptUser(token){
		var result =  { email: '', role: userRoles.public };
		if (token !== undefined)
		{
			if (verifyToken(token)){
				
				var pClaim = readToken(token);
				var roleUser;
				if (pClaim.role == 1){
					roleUser = userRoles.user;
				}else if(pClaim.role == 2){
					roleUser = userRoles.admin;
				} else{
					roleUser = userRoles.public;
				}
				result = {	email: pClaim.email, role: roleUser };
			}else{
				delete $window.sessionStorage.token;
			}
		}
		return result;
	}

	
    return {
        authorize: function(accessLevel, role) {
            if (accessLevel === undefined)
                accessLevel = userRoles.admin;
            if(role === undefined)
                role = currentUser.role;

            return accessLevel.bitMask & role.bitMask;
        },
        isLoggedIn: function(user) {
            if(user === undefined)
                user = currentUser;
            return verifyToken($window.sessionStorage.token) && (user.role.title == userRoles.user.title || user.role.title == userRoles.admin.title);
        },
		adaptCurrentUser : function(){
			changeUser(adaptUser($window.sessionStorage.token));
		},
        login: function(user, success, error) {
            $http.post('/api/login', user).success(function(token){
				var adaptedUser = adaptUser(token.value);
				changeUser(adaptedUser);
                success(token.value);
            }).error(error);
        },
		modificationPassword: function(user, success, error) {
            $http.post('/api/modificationPassword', user).success(function(token){
				var adaptedUser = adaptUser(token.value);
                success(token.value);
            }).error(error);
        },
		sendMailToken: function(user, success, error) {
            $http.post('/api/sendMailToken', user).success(success).error(error);
        },
		post: function(objet, success, error) {
            if (currentUser.role == userRoles.admin){
			    $http.post('/api/utilisateur', objet).success(success).error(error);
            }else{
                $http.post('/api/monprofil', objet).success(success).error(error);
            }
		},
		get: function(success, error) {
			$http.get('/api/profil').success(success).error(error);
		},
        accessLevels: accessLevels,
        userRoles: userRoles,
        user: currentUser
    };
}]);

})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('DashboardCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','PoidsFactory','MesureFactory', function($rootScope, $scope, $location, $route, $window, Auth,PoidsFactory,MesureFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

	$scope.getDataDashBoard = function () {

        if ($scope.user.role == $scope.userRoles.admin){
            $scope.success = '';
		    $scope.error = '';
		    $scope.loading = true;
		    MesureFactory.listNotificationsAdmin( 
			    function (res) {
				    $scope.loading = false;
				    var data = $.map(res, function(el, i) {
				      return [[el.ID,el.DATEHEUREMODIFICATION,el.EMAIL,el.DATEHEUREMESURE,'']];
				    });
				    var table = $("#notifications").dataTable({
					    "aaData": data,
					    "aoColumns": [
						    { "sTitle": "Id" },
                            { "sTitle": "Date/Heure modification" },
						    { "sTitle": "Email" },
						    { "sTitle": "Date/Heure repas" },
						    { "sTitle": "Action" }
					    ],
					    "oLanguage": {
					      	"sSearch": "Recherche:",
						    "sZeroRecords": "Pas d'éléménts à afficher",
						    "sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
						    "sInfoEmpty": "Pas d'éléments",
						    "sInfoFiltered": " - filtrés sur _MAX_ éléments",
						    "sLengthMenu": "Afficher _MENU_ éléments",					
						    "oPaginate": {
							    "sFirst": "Premier",
							    "sLast" : "Dernier",
							    "sNext" : "Suivant",
							    "sPrevious" : "Précédent"
						      }
					    },
					    "aoColumnDefs": [
						    { 
							    "targets": 0, 
							    "visible" : false,
                    			"searchable": false
						    },
						    { 
							    "targets": 1, 
							    "sType": "html", 
							    "render": function(data, type, row) {
								    var dateHeureMesureTab = data.split(' ');
                                    var dateMesureTab = dateHeureMesureTab[0].split('-');
                                    var dateMesureFr=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
								    var result = dateMesureFr.concat(" " ).concat(dateHeureMesureTab[1]);
								    return $("<div/>").html(result).text();
							    } 
						    },
						    { 
							    "targets": 3, 
							    "sType": "html", 
							    "render": function(data, type, row) {
								    var dateHeureMesureTab = data.split(' ');
                                    var dateMesureTab = dateHeureMesureTab[0].split('-');
                                    var dateMesureFr=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
								    var result = dateMesureFr.concat(" " ).concat(dateHeureMesureTab[1]);
								    return $("<div/>").html(result).text();
							    } 
						    },
						    { 
							    "aTargets": [4], 
							    "sType": "html", 
							    "render": function(data, type, row) {
								    var result = "";
								    var resultTmp = "";
								    var id = "";
								    var fin = "";
								    // Modification
								    resultTmp = "&lt;a href=&quot;/repas/";
								    id = row[0];
								    fin = "&quot;&gt;&lt;span class=&quot;fa fa-pencil&quot;&gt;&lt;/span&gt;&lt;/a&gt;";
								    result = resultTmp.concat(id).concat(fin);	
								    
								    return $("<div/>").html(result).text();
							    } 
						    }
					    ],
                        "order": [[ 1, "asc" ]]
				    });
			    },
			    function (err) {
				    $scope.error = "Impossible de recuperer les notifications";
				    $scope.loading = false;
			    }
		    );
        }else{
            var data = [];

            MesureFactory.getLastMesure(
                function(res) {
                    //Mise à jour des informations poids
                     _.each(res,function(mesure){
                        if (mesure.TYPE=='POIDS'){
                            $scope.lastPoids=mesure.POIDS;
                            $scope.lastPoidsDate='le ' + mesure.DATEMESURE;
                        }else{
                            if (mesure.TYPE=='REPAS'){
                                $scope.lastRepasDate='le ' + mesure.DATEMESURE + ' à ' + mesure.HEUREMESURE;
                            }
                        }
                    });
                },
                function(err) {
                    $scope.error = err;
                }
            );		

            PoidsFactory.list($scope.user.email,
                function(res) {
                    _.each(res,function(poids){
                        var dateMesureTab = poids.DATEMESURE.split('-');
                        var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                        data.push({'value' : poids.POIDS, 'date' : new Date(dateMesureEn)});
                    });
                    $scope.dataPoids=data;
                },
                function(err) {
                    $scope.error = err;
                }
            );	
        }	
	};

    switch (action) {
        case 'dashboard':
            $scope.getDataDashBoard();
        break;
        default:
        break;
    }


}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('MesureCtrl',
['$rootScope', '$scope', '$location', '$route','$routeParams','$window','PoidsFactory','RepasFactory','MesureFactory','UtilisateurFactory','Auth', function($rootScope, $scope, $location, $route,$routeParams, $window, PoidsFactory,RepasFactory,MesureFactory,UtilisateurFactory, Auth) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    var id;
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.changeUserMesure = function() {
      if ($scope.usermesure.email == 'Tous'){
        $scope.showButtonViewPoidsCourbe=false;
      }else{
        $scope.showButtonViewPoidsCourbe=true;
      }
      $('#mesures').fullCalendar( 'refetchEvents' );
    };

    $scope.viewCourbePoids = function(){
        var data = [];

        PoidsFactory.list($scope.usermesure.email,
            function(res) {
                _.each(res,function(poids){
                    var dateMesureTab = poids.DATEMESURE.split('-');
                    var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                    data.push({'value' : poids.POIDS, 'date' : new Date(dateMesureEn)});
                });
                $scope.dataPoids=data;
            },
            function(err) {
                $scope.error = err;
            }
        );		
        $('#bs-courbePoids').modal('show');
    };

    $scope.listMesures = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;

        var allUser = {};
        allUser.email='Tous';
        allUser.id=0;
        allUser.role=0;

        $scope.usermesure=allUser;

        UtilisateurFactory.list(
	        function (res) {
	            $scope.success = 'Succes';
                var result = _.filter(res, function(user) {
                  return user.role <  2;
                });                

                result.unshift(allUser);
               
                $scope.users = result;
	        },
	        function (err) {
	            $scope.error = err;	            
	        }
        );

        $('#mesures').fullCalendar({
            eventClick: function(calEvent, jsEvent, view) {
		        $scope.id = calEvent.id;
                if (calEvent.type == 'POIDS'){
                    $scope.createPoidsLoad(calEvent.id);
                }else{
                    $scope.createRepasLoad(calEvent.id);
                }
            },
            header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,agendaWeek,agendaDay'
			},
			defaultView: 'basicWeek',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: function(start, end, timezone, callback) {
                var dateStart=start._d;
                var dateStartString=dateStart.getFullYear() + '-' + (dateStart.getMonth() + 1) + '-' + dateStart.getDate();
                var dateEnd=end._d;
                var dateEndString=dateEnd.getFullYear() + '-' + (dateEnd.getMonth() + 1) + '-' + dateEnd.getDate();
                MesureFactory.list($scope.usermesure.email,dateStartString,dateEndString,
		        function (res) {
		            $scope.success = 'Succes';
		            var events = [];
                    _.each(res,function(mesure){
                        if (mesure.TYPE == 'POIDS'){
                            var dateMesureTab = mesure.DATEMESURE.split('-');
                            var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                            events.push({
                                id : mesure.ID,
                                title: mesure.EMAIL + ' : ' + mesure.POIDS,
                                start: dateMesureEn, // will be parsed
                                backgroundColor: "#00c0ef", //Info (aqua)
                                borderColor: "#00c0ef", //Info (aqua)
                                editable : false,
                                type : 'POIDS'
                            });
                        }else{
                            if (mesure.TYPE == 'REPAS'){
                                var eventValue={
                                        id : mesure.ID,
                                        title: mesure.EMAIL,
                                        start: mesure.DATEHEUREMESURE, // will be parsed
                                        allDay : false,
                                        editable : false,
                                        type : 'REPAS'
                                    };
                                if (mesure.COMMENTAIREDIET === ''){
                                    eventValue.backgroundColor = "#F39C12";
                                    eventValue.borderColor = "#E08E0B";
                                    events.push(eventValue);
                                }else{
                                    eventValue.backgroundColor = "#5CB85C";
                                    eventValue.borderColor = "#4CAE4C";
                                    events.push(eventValue);
                                }
                            }
                        }
                    });
                    callback(events);
		        },
		        function (err) {
		            $scope.error = err;
		        });
            }
		});
        
    };

    $scope.listMesMesures = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;

        var allUser = {};
        allUser.email= $scope.user.email;
        allUser.id=0;
        allUser.role=0;

        $scope.usermesure=allUser;

        $('#mesures').fullCalendar({
            eventClick: function(calEvent, jsEvent, view) {
		        $scope.id = calEvent.id;
                if (calEvent.type == 'POIDS'){
                    $scope.createPoidsLoad(calEvent.id);
                }else{
                    $scope.createRepasLoad(calEvent.id);
                }
            },
            header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,agendaWeek,agendaDay'
			},
			defaultView: 'basicWeek',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: function(start, end, timezone, callback) {
                var dateStart=start._d;
                var dateStartString=dateStart.getFullYear() + '-' + (dateStart.getMonth() + 1) + '-' + dateStart.getDate();
                var dateEnd=end._d;
                var dateEndString=dateEnd.getFullYear() + '-' + (dateEnd.getMonth() + 1) + '-' + dateEnd.getDate();
                MesureFactory.listMine(dateStartString,dateEndString,
		        function (res) {
		            $scope.success = 'Succes';
		            var events = [];
                    _.each(res,function(mesure){
                        if (mesure.TYPE == 'POIDS'){
                            var dateMesureTab = mesure.DATEMESURE.split('-');
                            var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                            events.push({
                                id : mesure.ID,
                                title: 'Poids : ' + mesure.POIDS,
                                start: dateMesureEn, // will be parsed
                                backgroundColor: "#00c0ef", //Info (aqua)
                                borderColor: "#00c0ef", //Info (aqua)
                                editable : false,
                                type : 'POIDS'
                            });
                        }else{
                            if (mesure.TYPE == 'REPAS'){
                                var eventValue={
                                        id : mesure.ID,
                                        title: 'Repas : ' + mesure.REPAS,
                                        start: mesure.DATEHEUREMESURE, // will be parsed
                                        allDay : false,
                                        editable : false,
                                        type : 'REPAS'
                                    };
                                if (mesure.COMMENTAIREDIET === ''){
                                    eventValue.backgroundColor = "#F39C12";
                                    eventValue.borderColor = "#E08E0B";
                                    events.push(eventValue);
                                }else{
                                    eventValue.backgroundColor = "#5CB85C";
                                    eventValue.borderColor = "#4CAE4C";
                                    events.push(eventValue);
                                }
                            }
                        }
                    });
                    callback(events);
		        },
		        function (err) {
		            $scope.error = err;
		        });
            }
		});
        
    };

    $scope.supprimerPoids = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cette mesure de poids?");
        if (retVal === true) {
		    PoidsFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
	                $('#mesures').fullCalendar( 'refetchEvents' );
	                $('#bs-poids').modal('hide');
		        },
		        function (err) {
		            $scope.error = err;
		            $('#mesures').fullCalendar( 'refetchEvents' );
	                $('#bs-poids').modal('hide');
		        });
		}
    };

    $scope.supprimerRepas = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer ce repas?");
        if (retVal === true) {
		    RepasFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
	                $('#mesures').fullCalendar( 'refetchEvents' );
	                $('#bs-repas').modal('hide');
		        },
		        function (err) {
		            $scope.error = err;
		            $('#mesures').fullCalendar( 'refetchEvents' );
	                $('#bs-repas').modal('hide');
		        });
		}
    };

    

    $scope.createPoidsLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
        $scope.userTous = 'false';
        $scope.doublon = 'false';
        $scope.abonnementinactif = 'false';

        if (id===undefined){
            $scope.dateMesure = "";
            $scope.poidsMesure = "";
            $scope.commentaireMesure ='';
		    $scope.id = "";
        }else{
            var modeSaisieMesure = "";
            if ($scope.mesures === true){
                modeSaisieMesure = "mesures";
            }else{
                if ($scope.mesmesures === true){
                    modeSaisieMesure = "mesmesures";
                }
            }
            PoidsFactory.get(id,modeSaisieMesure,
                function (res) {
                    $scope.success = 'Succes';
                    $scope.dateMesure = res.DATEMESURE;
                    $scope.poidsMesure = res.POIDS;
                    $scope.commentaireMesure = res.COMMENTAIRE;
		            $scope.id = id;
                    $scope.emailMesure = res.EMAIL;
                },
                function (err) {
                    $scope.error = err;
                    if (err == 'Doublon') {
                        $scope.doublon = 'true';
                    }
                });
        }

		$('#dateMesure').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.dateMesure = e.currentTarget.value;
        });
        
		$('#bs-poids').modal('show');

		
    };

    $scope.createRepasLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
        $scope.userTous = 'false';
        $scope.doublon = 'false';
        $scope.abonnementinactif = 'false';

        if (id===undefined){
            $scope.dateRepasMesure = "";
            $scope.heureRepasMesure = "";
            $scope.repasMesure = "";
            $scope.commentaireRepasMesure ='';
            $scope.commentaireRepasDietMesure ='';
		    $scope.id = "";
        }else{
            var modeSaisieMesure = "";
            if ($scope.mesures === true){
                modeSaisieMesure = "mesures";
            }else{
                if ($scope.mesmesures === true){
                    modeSaisieMesure = "mesmesures";
                }
            }
            RepasFactory.get(id,modeSaisieMesure,
                function (res) {
                    $scope.success = 'Succes';
                    $scope.dateRepasMesure = res.DATEMESURE;
                    $scope.heureRepasMesure = res.HEUREMESURE;
                    $scope.repasMesure = res.REPAS;
                    $scope.commentaireRepasMesure = res.COMMENTAIRE;
                    $scope.commentaireRepasDietMesure = res.COMMENTAIREDIET;
		            $scope.id = id;
                    $scope.emailMesure = res.EMAIL;
                },
                function (err) {
                    $scope.error = err;
                    if (err == 'Doublon') {
                        $scope.doublon = 'true';
                    }
                });
        }

		$('#dateRepasMesure').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.dateRepasMesure = e.currentTarget.value;
        });

        $('#heureRepasMesure').timepicker({
            showMeridian: false
            }
            ).on('changeTime.timepicker', function(e) {
                $scope.heureRepasMesure = e.time.value;
        });
        
		$('#bs-repas').modal('show');

		
    };

    $scope.addPoids = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        $scope.abonnementinactif = 'false';
        $scope.userTous = 'false';

        var objetValue = {};
	    objetValue.dateMesure=$scope.dateMesure;
	    objetValue.poidsMesure=$scope.poidsMesure;
	    objetValue.commentaireMesure=$scope.commentaireMesure;

        var modeSaisieMesure = "";
        if ($scope.mesures === true){
            modeSaisieMesure = "mesures";
        }else{
            if ($scope.mesmesures === true){
                modeSaisieMesure = "mesmesures";
            }
        }
        objetValue.email=$scope.usermesure.email;

        if ($scope.id === ""){
            if (objetValue.email=='Tous'){
                $scope.userTous = 'true';
            }else{
                PoidsFactory.put(objetValue,modeSaisieMesure,
	                function () {
	                    $scope.success = 'Succes';
		                $('#mesures').fullCalendar( 'refetchEvents' );
		                $('#bs-poids').modal('hide');
	                },
	                function (err) {
	                    $scope.error = err;
	                    if (err == 'Doublon') {
	                        $scope.doublon = 'true';
	                    }
                        if (err == 'AbonnementInactif') {
	                        $scope.abonnementinactif = 'true';
	                    }
	                });
            }
        }else{
            objetValue.id=$scope.id;
            PoidsFactory.post(objetValue,modeSaisieMesure,
	            function () {
	                $scope.success = 'Succes';
                    $('#mesures').fullCalendar( 'refetchEvents' );
		            $('#bs-poids').modal('hide');
	            },
	            function (err) {
	                $scope.error = err;
	                if (err == 'Doublon') {
	                    $scope.doublon = 'true';
	                }
                    if (err == 'AbonnementInactif') {
                        $scope.abonnementinactif = 'true';
                    }
	            });
            
        }
    };

    $scope.addRepas = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        $scope.abonnementinactif = 'false';
        $scope.userTous = 'false';

        var objetValue = {};
	    objetValue.dateRepasMesure=$scope.dateRepasMesure;
        objetValue.heureRepasMesure=$scope.heureRepasMesure;
	    objetValue.repasMesure=$scope.repasMesure;
	    objetValue.commentaireRepasMesure=$scope.commentaireRepasMesure;
        objetValue.commentaireRepasDietMesure=$scope.commentaireRepasDietMesure;

        var modeSaisieMesure = "";
        if ($scope.mesures === true){
            modeSaisieMesure = "mesures";
        }else{
            if ($scope.mesmesures === true){
                modeSaisieMesure = "mesmesures";
            }
        }
        objetValue.email=$scope.usermesure.email;

        if ($scope.id === ""){
            if (objetValue.email=='Tous'){
                $scope.userTous = 'true';
            }else{
                RepasFactory.put(objetValue,modeSaisieMesure,
	                function () {
	                    $scope.success = 'Succes';
		                $('#mesures').fullCalendar( 'refetchEvents' );
                        $('#bs-repas').modal('hide');
	                },
	                function (err) {
	                    $scope.error = err;
	                    if (err == 'Doublon') {
	                        $scope.doublon = 'true';
	                    }
                        if (err == 'AbonnementInactif') {
	                        $scope.abonnementinactif = 'true';
	                    }
	                });
            }
        }else{
            objetValue.id=$scope.id;
            RepasFactory.post(objetValue,modeSaisieMesure,
	            function () {
	                $scope.success = 'Succes';
                    $('#mesures').fullCalendar( 'refetchEvents' );
		            $('#bs-repas').modal('hide');
	            },
	            function (err) {
	                $scope.error = err;
	                if (err == 'Doublon') {
	                    $scope.doublon = 'true';
	                }
                    if (err == 'AbonnementInactif') {
                        $scope.abonnementinactif = 'true';
                    }
	            });
            
        }
    };


    switch (action) {
        case 'listMesMesures':
            $scope.listMesMesures();
        break;
        case 'listMesures':
            $scope.listMesures();
        break;
        case 'addPoids':
            $scope.mesmesures = true;
            $scope.listMesMesures();
            $scope.createPoidsLoad();
        break;
        case 'addRepas':
            $scope.mesmesures = true;
            $scope.listMesMesures();
            $scope.createRepasLoad();
        break;
        case 'modifRepas':
            $scope.mesures = true;
            $scope.listMesures();
            id = $routeParams.id;
            $scope.createRepasLoad(id);
        break;
        case 'modifMonRepas':
            $scope.mesmesures = true;
            $scope.listMesMesures();
            id = $routeParams.id;
            $scope.createRepasLoad(id);
        break;
        default:
        break;
    }
    
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition').factory('PoidsFactory',['$http', function($http) {

    return {
        list: function(email,success, error) {
            $http.get('/api/listpoids/'+email).success(success).error(error);
        },
        get: function(id,modeSaisieMesure,success, error) {
            if (modeSaisieMesure === "mesures"){
                $http.get('/api/poids/'+id).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
                    $http.get('/api/monpoids/'+id).success(success).error(error);
                }
            }
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/poids/' + id
			}).success(success).error(error);			
		},
        put: function(objet,modeSaisieMesure, success, error) {
            if (modeSaisieMesure === "mesures"){
			    $http.put('/api/poids', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.put('/api/monpoids', objet).success(success).error(error);
                }
            }
		},
		post: function(objet,modeSaisieMesure, success, error) {
			if (modeSaisieMesure === "mesures"){
			    $http.post('/api/poids', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.post('/api/monpoids', objet).success(success).error(error);
                }
            }
		}
    };
}]);

angular.module('EspaceNutrition').factory('RepasFactory',['$http', function($http) {

    return {
        get: function(id,modeSaisieMesure,success, error) {
            if (modeSaisieMesure === "mesures"){
                $http.get('/api/repas/'+id).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
                    $http.get('/api/monrepas/'+id).success(success).error(error);
                }
            }
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/repas/' + id
			}).success(success).error(error);			
		},
        put: function(objet,modeSaisieMesure, success, error) {
            if (modeSaisieMesure === "mesures"){
			    $http.put('/api/repas', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.put('/api/monrepas', objet).success(success).error(error);
                }
            }
		},
		post: function(objet,modeSaisieMesure, success, error) {
			if (modeSaisieMesure === "mesures"){
			    $http.post('/api/repas', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.post('/api/monrepas', objet).success(success).error(error);
                }
            }
		}
    };
}]);

angular.module('EspaceNutrition').factory('MesureFactory',['$http', function($http) {

    return {
        list: function(email,dateStart,dateEnd,success, error) {
            $http.get('/api/mesures/'+email+'/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        listMine: function(dateStart,dateEnd,success, error) {
            $http.get('/api/mesmesures/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        getLastMesure : function(success, error) {
            $http.get('/api/lastmesures').success(success).error(error);
        },
        listNotificationsAdmin: function(success, error) {
            $http.get('/api/notificationsAdmin').success(success).error(error);
        },
        listNotificationsUser: function(success, error) {
            $http.get('/api/notificationsUser').success(success).error(error);
        }
    };
}]);


})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('PaiementCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','Auth', 'PaiementFactory', function($rootScope, $scope, $location, $route, $window,Auth, PaiementFactory) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
	var action = "";
	if ($route !== undefined && $route.current){
		
		if ($route.current.action !== undefined){
			action = $route.current.action;
		}
	}

	$scope.listPaiement = function () {
		$scope.success = '';
		$scope.error = '';
		$scope.loading = true;
		PaiementFactory.list( 
			function (res) {
				$scope.loading = false;
				var data = $.map(res, function(el, i) {
				  return [[el.business, el.txnid, el.payment_amount, el.payment_status, el.item_name, el.payer_id, el.payer_first_name, el.payer_last_name, el.payer_email, el.createdtime,el.mode]];
				});
				var table = $("#paiements").dataTable({
					"aaData": data,
					"aoColumns": [
						{ "sTitle": "Destinataire" },
						{ "sTitle": "Txn ID" },
						{ "sTitle": "Montant" },
						{ "sTitle": "Statut" },
						{ "sTitle": "Item" },
						{ "sTitle": "Id payeur" },
						{ "sTitle": "Prénom payeur" },
						{ "sTitle": "Nom payeur" },
						{ "sTitle": "Email payeur" },
						{ "sTitle": "Date paiement" },
						{ "sTitle": "Mode" }
					],
					"oLanguage": {
					  	"sSearch": "Recherche:",
						"sZeroRecords": "Pas d'éléménts à afficher",
						"sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
						"sInfoEmpty": "Pas d'éléments",
						"sInfoFiltered": " - filtrés sur _MAX_ éléments",
						"sLengthMenu": "Afficher _MENU_ éléments",					
						"oPaginate": {
							"sFirst": "Premier",
							"sLast" : "Dernier",
							"sNext" : "Suivant",
							"sPrevious" : "Précédent"
						  }
					},
					"order": [[ 9, "desc" ]]
				});
			},
			function (err) {
				$scope.error = "Impossible de recuperer les paiements";
				$scope.loading = false;
			}
		);
	};

	switch (action) {
		case 'listPaiement':
			$scope.listPaiement();
		break;
		default:
		break;
	}
	
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('PaiementFactory',['$http', function($http) {

	return {
		list: function(success, error) {
			$http.get('/api/paiements').success(success).error(error);
		}
	};
}]);


})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionPublicCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','PublicFactory', function($rootScope, $scope, $location, $route, $window,PublicFactory) {
    
	var action = "";
	$scope.ph_numbr = /^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$/;
	
	if ($route !== undefined && $route.current){
		
		if ($route.current.action !== undefined){
			action = $route.current.action;
		}
	}

    $scope.affichePopupPaiement = function (id) {
        $scope.success = '';
        $scope.error = '';
		$scope.idPaiement=id;
		$('#bs-paiement').modal('show');
    };
	
	$scope.redirectionPaiement = function () {
		$('#bs-paiement').modal('hide');
		
		var objetValue = {};
		objetValue.ref=$scope.idPaiement;
		objetValue.nom=$scope.nom;
		objetValue.prenom=$scope.prenom;
		objetValue.telephone=$scope.telephone;
		objetValue.email=$scope.email;
		objetValue.adresse=$scope.adresse;
		objetValue.moyen=$scope.moyen;
		objetValue.acceptation=$scope.acceptation;		

		PublicFactory.enregistrerCommande(objetValue,
			function (res) {
				$scope.loading = false;
				$('#bs-afficheMoyenPaiement').modal('show');
			},
			function (err) {
				$scope.error = "Impossible d enregistrer la commande";
				$scope.loading = false;
			}
		);		
    };

	$scope.sendMessage = function () {
		$scope.success = "";
		if ($scope.champControl === undefined){
			var objetValue = {};
			objetValue.email=$scope.email;
			objetValue.nom=$scope.nom;
			objetValue.telephone=$scope.telephone;
			objetValue.message=$scope.message;

			PublicFactory.sendMessage(objetValue,
					function () {
						$scope.initFieldContact();
						$scope.success = 'Message envoyé avec succès';
					},
					function (err) {
						$scope.error = err;
					});
		}
	};
	
	$scope.listTemoignages = function (page) {
		
		$scope.success = '';
		$scope.error = '';
		$scope.loading = true;

		PublicFactory.listTemoignages(page,
			function (res) {
				$scope.loading = false;
				$scope.temoignages = res.result;
				$scope.links = res.links;
			},
			function (err) {
				$scope.error = "Impossible de recuperer les temoignages";
				$scope.loading = false;
			}
		);
	}
	
	$scope.first = function(){
		$scope.listTemoignages($scope.links.first.page);
	};

	$scope.previous = function(){
		$scope.listTemoignages($scope.links.previous.page);
	};

	$scope.next = function(){
		$scope.listTemoignages($scope.links.next.page);
	};

	$scope.last = function(){
		$scope.listTemoignages($scope.links.last.page);
	};
	
	$scope.affichePopupAddTemoignagne = function (id) {
        $scope.success = '';
        $scope.error = '';
		$('#aTemoignageModal').modal('show');
    };
	
	$scope.affichePopupMentionsLegales = function (id) {
		$('#bs-ml').modal('show');
    };
	
	$scope.addTemoignage = function () {
        $('#aTemoignageModal').modal('hide');
		
		var objetValue = {};
		objetValue.prenom=$scope.prenom;
		objetValue.age=$scope.age;
		objetValue.objectif=$scope.objectif;
		objetValue.temoignage=$scope.temoignage;

		PublicFactory.addTemoignage(objetValue,
				function () {
					$('#aTemoignageSuccessModal').modal('show');
				},
				function (err) {
					$scope.error = err;
				});
		
    };

	$scope.initFieldContact = function() {
		$scope.sliderValue=0;
		$scope.initValueWait = Math.floor((Math.random() * 100) + 1);
	};

	switch (action) {
		case 'paiementSuccess':
			$scope.paiementSuccess();
			$scope.initFieldContact();
            $('.carousel').carousel();
		break;
		default:
			$scope.initFieldContact();
            $('.carousel').carousel();
			$scope.listTemoignages(1);
		break;
	}
	
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition').directive('slider', function() {
    return {
        link: function(scope, element, attrs) {
            // Linking function.
			var $element = $(element);
			var $bar = $('span', $element);
			var step = attrs.step;

			var width;
			var offset;

			var mouseDown = false;
			element.on('mousedown touchstart', function(evt) {
				mouseDown = true;
				if (!width) {
					width = $element.width();
				} if (!offset) {
					offset = $bar.offset().left;
				}
			});

			element.on('mouseup touchend', function(evt) {
				mouseDown = false;
			});
            // Throttle function to 1 call per 25ms for performance.
			element.on('mousemove touchmove', _.throttle(function(evt) {
				if (!mouseDown) {
					// Don't drag the slider on mousemove hover, only on click-n-drag.
					return;
				}

				// Calculate distance of the cursor/finger from beginning of slider
				var diff;
				if (evt.pageX) {
					diff = evt.pageX - offset;
				} else {
					diff = evt.originalEvent.touches[0].pageX - offset;
				}
                // Allow dragging past the limits of the slider, but impose min/max values.
				if (diff < 0) {
					scope.sliderValue = attrs.min;
					$bar.width('0%');
				} else if (diff > width) {
					scope.sliderValue = attrs.max;
					$bar.width('100%');

				// Set the value to percentage of slider filled against a max value.
				} else {
					var percent = diff / width;
					$bar.width(percent * 100 + '%');
					scope.sliderValue = (Math.round(percent * attrs.max / step) * step);
				}

				// Let all the watchers know we have updated the slider value.
				scope.$apply();
			}, 25));
            scope.$watch('sliderValue', function(sliderValue) {
				$bar.width(sliderValue / attrs.max * 100 + '%');
			});
        }
    };
});

})();
(function(){
"use strict";

	angular.module('EspaceNutrition')
	.factory('PublicFactory', ['$http','$window', function($http,$window){

		var range;
		var rangeMin;
		var rangeMax;
		var nbTemoignagesParPages = 5;
		var uri;
		var uriRange='';
		
		return {
			listTemoignages: function(page,success, error) {
				if (page !== undefined ){
					
						rangeMin=parseInt(page)*nbTemoignagesParPages - nbTemoignagesParPages;
						rangeMax=parseInt(page)*nbTemoignagesParPages - 1;
						range=rangeMin.toString().concat("-").concat(rangeMax.toString());
						uriRange='range='+range;

					$http.get('/api/temoignages?'+uriRange).success(success).error(error);
				}else{
					$http.get('/api/temoignages').success(success).error(error);
				}
			},
			enregistrerCommande: function(id,success, error) {
				$http.put('/api/commande',id).success(success).error(error);
			},
			addTemoignage: function(objet, success, error) {
				$http.put('/api/temoignages', objet).success(success).error(error);
			},
			sendMessage: function(message,success, error) {
				$http.post('/api/sendMessage', message).success(function(){
		            success();
            	}).error(error);
			}
		};
	}]);

})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('TemoignageCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','Auth', 'TemoignageFactory', function($rootScope, $scope, $location, $route, $window, Auth,TemoignageFactory) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.listTemoignages = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;
        TemoignageFactory.list( 
            function (res) {
                $scope.loading = false;
				var data = $.map(res, function(el, i) {
					var valide_libelle="Vrai";
					if (el.valide=="0"){
						valide_libelle="Faux";
					}
					return [[el.id,el.prenom, el.age, el.objectif, el.date,valide_libelle,""]];
                });
				
                var table = $("#temoignages").dataTable({
                    "aaData": data,
                    "aoColumns": [
                        { "sTitle": "Id" },
                        { "sTitle": "Prénom" },
                        { "sTitle": "Age" },
                        { "sTitle": "Objectif " },
                        { "sTitle": "Date" },
						{ "sTitle": "Valide" },
                        { "sTitle": "Action" }
                    ],
                    "oLanguage": {
                        "sSearch": "Recherche:",
                        "sZeroRecords": "Pas d'éléménts à afficher",
                        "sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
                        "sInfoEmpty": "Pas d'éléments",
                        "sInfoFiltered": " - filtrés sur _MAX_ éléments",
                        "sLengthMenu": "Afficher _MENU_ éléments",                    
                        "oPaginate": {
                            "sFirst": "Premier",
                            "sLast" : "Dernier",
                            "sNext" : "Suivant",
                            "sPrevious" : "Précédent"
                          }
                    },
                    "aoColumnDefs": [   
                        { 
							"targets": 6, 
							"sType": "html", 
							"render": function(data, type, row) {
								var result = "";
								var resultTmp = "";
								var id = "";
								var fin = "";
								// Modification
								resultTmp = "&lt;button class=&quot;btn btn-link&quot; ng-click=&quot;update(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-pencil&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = resultTmp.concat(id).concat(fin);
								// Suppression
								resultTmp = "&lt;button class=&quot;btn btn-link app-btn-delete&quot; ng-click=&quot;delete(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-times&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = result.concat(resultTmp).concat(id).concat(fin);	
								return $("<div/>").html(result).text();
							} 
                        }
                    ],
                    "order": [[ 0, "desc" ]]
                    
                });
                $('#temoignages tbody').on( 'click', 'button', function () {
					var name = this.attributes[1].nodeName;
					var value = this.attributes[1].nodeValue;
					var tab = "";
					var result = "";
					if(name == "ng-click" && value.indexOf("delete") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.supprimer(result);
					}
					if(name == "ng-click" && value.indexOf("update") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.updateLoad(result);
					}
				} );
            },
            function (err) {
                $scope.error = "Impossible de recuperer les temoignages";
                $scope.loading = false;
            }
        );
    };
	
	$scope.updateLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		TemoignageFactory.get(id,
			function (res) {
				$scope.id = res.id;
				$scope.prenom = res.prenom;
				$scope.age = parseInt(res.age);
				$scope.objectif = res.objectif;
				$scope.temoignage = res.temoignage;
				$scope.date = res.date;
				$scope.valide = res.valide;
				$('#aTemoignageModal').modal('show');
			},
			function (err) {
				$scope.error = err;
			});
		
    };


    $scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer ce témoignage?");
        if (retVal === true) {
		    TemoignageFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
		            $route.reload();
		        },
		        function (err) {
		            $scope.error = err;
		            $route.reload();
		        });
		}
    };
	
	$scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        
		var objetValue = {};
		objetValue.prenom=$scope.prenom;
		objetValue.age=$scope.age;
		objetValue.objectif=$scope.objectif;
		objetValue.temoignage=$scope.temoignage;
		objetValue.date=$scope.date;
		objetValue.valide=$scope.valide;

		if ($scope.id === ""){
			TemoignageFactory.put(objetValue,
				function () {
					$scope.success = 'Succes';
					$('#aTemoignageModal').on('hidden.bs.modal', function (e) {
					  $route.reload();
					});
					$('#aTemoignageModal').modal('hide');
				},
				function (err) {
					$scope.error = err;
					if (err == 'Doublon') {
						$scope.doublon = 'true';
					}
				});
		}else{
			objetValue.id=$scope.id;
			TemoignageFactory.post(objetValue,
				function () {
					$scope.success = 'Succes';
					$('#aTemoignageModal').on('hidden.bs.modal', function (e) {
					  $route.reload();
					});
					$('#aTemoignageModal').modal('hide');
				},
				function (err) {
					$scope.error = err;
					if (err == 'Doublon') {
						$scope.doublon = 'true';
					}
				});
		}	
    };


    switch (action) {
        case 'listTemoignages':
            $scope.listTemoignages();
        break;
        default:
        break;
    }
    
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition').factory('TemoignageFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/temoignagess').success(success).error(error);
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/temoignagess/' + id
			}).success(success).error(error);			
		},
		get: function(id, success, error) {
			$http.get('/api/temoignagess/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/temoignagess', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/temoignagess', objet).success(success).error(error);
		}
    };
}]);

})();



(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('UtilisateurCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','UtilisateurFactory', function($rootScope, $scope, $location, $route, $window, Auth,UtilisateurFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

	
	var action = "";
	if ($route !== undefined && $route.current){
		
		if ($route.current.action !== undefined){
			action = $route.current.action;
		}
	}

	$scope.role = "1";


	$scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cet utilisateur?");
        if (retVal === true) {
		    UtilisateurFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
		            $route.reload();
		        },
		        function (err) {
		            $scope.error = err;
		            $route.reload();
		        });
		}
    };

	$scope.updateLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		UtilisateurFactory.get(id,
		        function (res) {
					$scope.email = res.EMAIL;
					$scope.nom = res.NOM;
					$scope.prenom = res.PRENOM;
					$scope.datenaissance = res.DATENAISSANCE;
					$scope.role = res.ROLE;
					$scope.actif = res.ACTIF;
					$scope.id = res.ID;
		            $scope.success = 'Succes';
					$('#dateNaissanceUtilisateur').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
                        $scope.datenaissance = e.currentTarget.value;
                    }); 
		            $('#bs-ajoututilisateur').modal('show');
		        },
		        function (err) {
		            $scope.error = err;
		        });
		
    };

	$scope.createLoad = function (id) {
        $scope.success = '';
        $scope.error = '';

		$scope.email = "";
		$scope.nom = "";
		$scope.prenom = "";
		$scope.datenaissance = "";
		$scope.role = 1;
		$scope.id = "";

		$('#dateNaissanceUtilisateur').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.datenaissance = e.currentTarget.value;
        });
		$('#bs-ajoututilisateur').modal('show');
		
    };

	$scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        var objetValue = {};
		objetValue.email=$scope.email;
		objetValue.nom=$scope.nom;
		objetValue.prenom=$scope.prenom;
		objetValue.datenaissance=$scope.datenaissance;
		objetValue.role=$scope.role;

		if ($scope.id === ""){
			UtilisateurFactory.put(objetValue,
				function () {
				    $scope.success = 'Succes';
					$('#bs-ajoututilisateur').on('hidden.bs.modal', function (e) {
					  $route.reload();
					});
					$('#bs-ajoututilisateur').modal('hide');
				},
				function (err) {
				    $scope.error = err;
				    if (err == 'Doublon') {
				        $scope.doublon = 'true';
				    }
				});
		}else{
			objetValue.id=$scope.id;
			objetValue.actif=$scope.actif;
			UtilisateurFactory.post(objetValue,
				function () {
				    $scope.success = 'Succes';
					$('#bs-ajoututilisateur').on('hidden.bs.modal', function (e) {
					  $route.reload();
					});
					$('#bs-ajoututilisateur').modal('hide');
				},
				function (err) {
				    $scope.error = err;
				    if (err == 'Doublon') {
				        $scope.doublon = 'true';
				    }
				});
		}
		
    };

	$scope.listUtilisateur = function () {
		$scope.success = '';
		$scope.error = '';
		$scope.loading = true;
		UtilisateurFactory.list( 
			function (res) {
				$scope.loading = false;
				var data = $.map(res, function(el, i) {
				  return [[el.id,el.email,el.nom,el.prenom,el.role,el.actif,""]];
				});
				var table = $("#utilisateurs").dataTable({
					"aaData": data,
					"aoColumns": [
						{ "sTitle": "Id" },
						{ "sTitle": "Email" },
						{ "sTitle": "Nom" },
						{ "sTitle": "Prénom" },
						{ "sTitle": "Role" },
						{ "sTitle": "Actif" },
						{ "sTitle": "Action" }
					],
					"oLanguage": {
					  	"sSearch": "Recherche:",
						"sZeroRecords": "Pas d'éléménts à afficher",
						"sInfo": "_START_ sur _END_ de _TOTAL_ éléments",
						"sInfoEmpty": "Pas d'éléments",
						"sInfoFiltered": " - filtrés sur _MAX_ éléments",
						"sLengthMenu": "Afficher _MENU_ éléments",					
						"oPaginate": {
							"sFirst": "Premier",
							"sLast" : "Dernier",
							"sNext" : "Suivant",
							"sPrevious" : "Précédent"
						  }
					},
					"aoColumnDefs": [
						{ 
							"targets": 0, 
							"visible" : false,
                			"searchable": false
						},
						{ 
							"targets": 4, 
							"sType": "html", 
							"render": function(data, type, row) {
								var spanOuv="&lt;span class=&quot;label ";
								var label = "";
								switch(data) {
									case "0":
										label="label-danger&quot;&gt;Non autorisé";
										break;
									case "1":
										label="label-info&quot;&gt;Utilisateur";
										break;
									default:
										label="label-success&quot;&gt;Admin";
								} 
									
								var spanFerm = "&lt;/span&gt;";
								var result = spanOuv.concat(label).concat(spanFerm);
								return $("<div/>").html(result).text();
							} 
						},
						{ 
							"targets": 5, 
							"sType": "html", 
							"render": function(data, type, row) {
								var spanOuv="&lt;span class=&quot;label ";
								var label = "";
								switch(data) {
									case "0":
										label="label-info&quot;&gt;Non actif";
										break;
									case "1":
										label="label-success&quot;&gt;Actif";
										break;
									default:
										label="label-danger&quot;&gt;???";
								} 
									
								var spanFerm = "&lt;/span&gt;";
								var result = spanOuv.concat(label).concat(spanFerm);
								return $("<div/>").html(result).text();
							} 
						},
						{ 
							"aTargets": [6], 
							"sType": "html", 
							"render": function(data, type, row) {
								var result = "";
								var resultTmp = "";
								var id = "";
								var fin = "";
								// Modification
								resultTmp = "&lt;button class=&quot;btn btn-link&quot; ng-click=&quot;update(";
								id = row[0];
								fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-pencil&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								result = resultTmp.concat(id).concat(fin);	
								// Suppression
								if ($scope.user.email != row[1])
								{
									resultTmp = "&lt;button class=&quot;btn btn-link app-btn-delete&quot; ng-click=&quot;delete(";
									id = row[0];
									fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-times&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
									result = result.concat(resultTmp).concat(id).concat(fin);	
								}
								return $("<div/>").html(result).text();
							} 
						}
					]
				});
				$('#utilisateurs tbody').on( 'click', 'button', function () {
					var name = this.attributes[1].nodeName;
					var value = this.attributes[1].nodeValue;
					var tab = "";
					var result = "";
					if(name == "ng-click" && value.indexOf("delete") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.supprimer(result);
					}
					if(name == "ng-click" && value.indexOf("update") != -1){
						tab = value.split("(");
						result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.updateLoad(result);
					}
				} );
			},
			function (err) {
				$scope.error = "Impossible de recuperer les utilisateurs";
				$scope.loading = false;
			}
		);
	};

	switch (action) {
		case 'listUtilisateur':
			$scope.listUtilisateur();
		break;
		default:
		break;
	}
	
}]);

})();
(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('UtilisateurFactory',['$http', function($http) {
	var userRoles = routingConfig.userRoles;

	return {
		list: function(success, error) {
			$http.get('/api/utilisateurs').success(success).error(error);
		},
		add: function(objet, success, error) {
			$http.post('/api/utilisateur', objet).success(success).error(error);
		},
		get: function(id, success, error) {
			$http.get('/api/utilisateur/' + id).success(success).error(error);
		},
		supprimer: function(id, success, error) {
			//using $http.delete() throws a parse error in IE8
			// $http.delete('/api/utilisateur/' + id).success(success).error(error);
			$http({
				method: 'DELETE', 
				url: '/api/utilisateur/' + id
			}).success(success).error(error);			
		},
		put: function(objet, success, error) {
			$http.put('/api/utilisateur', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/utilisateur', objet).success(success).error(error);
		},
		userRoles : userRoles
	};
}]);

})();



