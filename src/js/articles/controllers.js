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
