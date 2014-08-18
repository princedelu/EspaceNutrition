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

	$scope.delete = function (id) {
        $scope.success = '';
        $scope.error = '';
        UtilisateurFactory.delete(id,
            function () {
                $scope.success = 'Succes';
                $route.reload();
            },
            function (err) {
                $scope.error = err;
                $route.reload();
            });
    };

	$scope.list = function () {
		$scope.success = '';
		$scope.error = '';
		$scope.loading = true;
		UtilisateurFactory.list( 
			function (res) {
				$scope.loading = false;
				var data = $.map(res, function(el, i) {
				  return [[el.id,el.username,el.nom,el.prenom,el.email,el.role,""]];
				});
				var table = $("#utilisateurs").dataTable({
					"aaData": data,
					"aoColumns": [
						{ "sTitle": "Id" },
						{ "sTitle": "Login" },
						{ "sTitle": "Nom" },
						{ "sTitle": "Prénom" },
						{ "sTitle": "Email" },
						{ "sTitle": "Role" },
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
							"aTargets": [5], 
							"sType": "html", 
							"fnRender": function(o, val) {
								var spanOuv="&lt;span class=&quot;label ";
								switch(o.aData[5]) {
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
							"aTargets": [6], 
							"sType": "html", 
							"fnRender": function(o, val) {
								var result="&lt;button class=&quot;btn btn-link app-btn-delete&quot; ng-click=&quot;delete("
								var id = o.aData[0];
								var fin = ")&quot; type=&quot;button&quot;&gt;&lt;span class=&quot;fa fa-times&quot;&gt;&lt;/span&gt;&lt;/button&gt;";
								return $("<div/>").html(result.concat(id).concat(fin)).text();
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
						$scope.delete(result);
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
