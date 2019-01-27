(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionPublicCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','PublicFactory', function($rootScope, $scope, $location, $route, $window,PublicFactory) {
    
	var action = "";
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
		
		var orderRef = new Date();
		var data = {
			vendor_token		: routingConfig.lydia.vendor_token,
			amount				: routingConfig.item[$scope.idPaiement].amount,
			recipient			: $scope.numeroportable,
			order_ref 			: orderRef.toString(),
			browser_success_url : routingConfig.lydia.urlReturn,
			browser_cancel_url 	: routingConfig.lydia.urlCancel,
			confirm_url 		: routingConfig.lydia.urlNotify,
			message 			: routingConfig.item[$scope.idPaiement].libelle,
			payer_desc 			: "test",
			collector_desc 		: routingConfig.item[$scope.idPaiement].libelle,
			currency			: "EUR",
			type				: "phone"
		};
		
		var baseURL = 'https://';
		if (routingConfig.lydia.sandbox === true){
			baseURL = baseURL + 'homologation.';
		}
		baseURL = baseURL + 'lydia-app.com/api/request/do.json';
		
		$.post(baseURL,
			data,
		    function(data) {
				if (data.error == 0) {
					document.location = data.mobile_url;
				} else {
					$scope.error=data.message;
				}
			}
		);		
    };

	$scope.paiementSuccess = function () {
		if  ($rootScope.affichePopup === undefined){
			$('#bs-paiementSuccess').modal('show');
			$rootScope.affichePopup = false;
		}
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
