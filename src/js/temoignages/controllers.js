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
