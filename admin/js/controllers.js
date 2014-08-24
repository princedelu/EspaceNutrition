(function(){
"use strict";

/* Controllers */
angular.module('Login')
.controller('LoginCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth', function($rootScope, $scope, $location, $route, $window, Auth) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

	if ($location.search().token !== undefined)
	{
		$scope.token = $location.search().token;
	}else{
		$scope.confirmpassword = "value";
	}
	

    $scope.login = function() {
		if ($scope.token === undefined){
		    Auth.login({
		            email: $scope.email,
		            password: $scope.password,
		        },
		        function(res) {
					$window.sessionStorage.token = res;
		            $window.location.href = '/admin/dashboard';
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
		            $window.location.href = '/admin/dashboard';
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


}]);

/* Controllers */
angular.module('EspaceNutrition')
.controller('LoginCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth', function($rootScope, $scope, $location, $route, $window, Auth) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    $scope.logout = function() {
		delete $window.sessionStorage.token;
		 $window.location.href = '/admin/login';
    };


}]);

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

	$('#dateNaissanceUtilisateur').datepicker({format: 'dd-mm-yyyy'}); 

	$scope.role = "1";

	$scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cet utilisateur?");
        if (retVal == true) {
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

		$('#bs-ajoututilisateur').modal('show');
		
    };

	$scope.monprofilLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		UtilisateurFactory.getProfil(
		        function (res) {
					$scope.email = res.EMAIL;
					$scope.nom = res.NOM;
					$scope.prenom = res.PRENOM;
					$scope.datenaissance = res.DATENAISSANCE;
					$scope.id = res.ID;
		            $scope.success = 'Succes';
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
			objetValue["email"]=$scope.email;
			objetValue["nom"]=$scope.nom;
			objetValue["password"]=$scope.password;
			objetValue["prenom"]=$scope.prenom;
			objetValue["datenaissance"]=$scope.datenaissance;
			objetValue["id"]=$scope.id;
			objetValue["profil"]=1;

			UtilisateurFactory.post(objetValue,
				function () {
					$scope.success = 'Succes';
					$('#bs-profil').modal('hide');
				},
				function (err) {
					$scope.error = err;
				});
		}
    };

	$scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        var objetValue = {};
		objetValue["email"]=$scope.email;
		objetValue["nom"]=$scope.nom;
		objetValue["prenom"]=$scope.prenom;
		objetValue["datenaissance"]=$scope.datenaissance;
		objetValue["role"]=$scope.role;

		if ($scope.id === ""){
			UtilisateurFactory.put(objetValue,
				function () {
				    $scope.success = 'Succes';
					$('#bs-ajoututilisateur').on('hidden.bs.modal', function (e) {
					  $route.reload()
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
			objetValue["id"]=$scope.id;
			objetValue["actif"]=$scope.actif;
			UtilisateurFactory.post(objetValue,
				function () {
				    $scope.success = 'Succes';
					$('#bs-ajoututilisateur').on('hidden.bs.modal', function (e) {
					  $route.reload()
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

	$scope.list = function () {
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
								switch(data) {
									case "0":
										var label="label-danger&quot;&gt;Non autorisé";
										break;
									case "1":
										var label="label-info&quot;&gt;Utilisateur";
										break;
									default:
										var label="label-success&quot;&gt;Admin";
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
								switch(data) {
									case "0":
										var label="label-info&quot;&gt;Non actif";
										break;
									case "1":
										var label="label-success&quot;&gt;Actif";
										break;
									default:
										var label="label-danger&quot;&gt;???";
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
					if(name == "ng-click" && value.indexOf("delete") != -1){
						var tab = value.split("(");
						var result = tab[1];
						tab = result.split(")");
						result = tab[0];
						$scope.supprimer(result);
					}
					if(name == "ng-click" && value.indexOf("update") != -1){
						var tab = value.split("(");
						var result = tab[1];
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
		case 'list':
			$scope.list();
			break;
		case 'get':
			var id = $routeParams.id;
			$scope.get(id);
			break;
		case 'add':
			break;
		default:
		break;
	}
}]);

})();
