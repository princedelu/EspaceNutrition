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
