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
		    objetValue.email=$scope.email;
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
