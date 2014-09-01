(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('PaiementCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'PaiementFactory', function($rootScope, $scope, $location, $route, $window, PaiementFactory) {
    
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
