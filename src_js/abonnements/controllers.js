(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('AbonnementCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','Auth', 'AbonnementFactory', function($rootScope, $scope, $location, $route, $window, Auth,AbonnementFactory) {

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
