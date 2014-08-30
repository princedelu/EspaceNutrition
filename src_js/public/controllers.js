(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionPublicCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', function($rootScope, $scope, $location, $route, $window) {
    
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
		$('#bs-paiement').modal('hide');

		// Ajout de toutes les informations pour la redirection
		var paramRedirect = "";
		paramRedirect=paramRedirect + "?business="+routingConfig.paypal.business;
		paramRedirect=paramRedirect + "&item_name="+routingConfig.item[$scope.idPaiement].libelle;
	    paramRedirect=paramRedirect + "&amount="+routingConfig.item[$scope.idPaiement].amount;
		
		paramRedirect=paramRedirect + "&cmd=_xclick";
		paramRedirect=paramRedirect + "&no_note=1";
		paramRedirect=paramRedirect + "&lc=FR";
		paramRedirect=paramRedirect + "&currency_code=EUR";
		paramRedirect=paramRedirect + "&bn=EspaceNutrition_BuyNow_WPS_FR";
		paramRedirect=paramRedirect + "&first_name="+$scope.prenom; 
		paramRedirect=paramRedirect + "&last_name="+$scope.nom; 
		paramRedirect=paramRedirect + "&payer_email="+$scope.email; 
		paramRedirect=paramRedirect + "&item_number=1";
		// Append paypal return addresses
	    paramRedirect=paramRedirect + "&return="+routingConfig.paypal.urlReturn;
	    paramRedirect=paramRedirect + "&cancel_return="+routingConfig.paypal.urlCancel;
	    paramRedirect=paramRedirect + "&notify_url="+routingConfig.paypal.urlNotify;

		var baseURL = 'https://www.';
		if (routingConfig.paypal.sandbox === true){
			baseURL = baseURL + 'sandbox.';
		}
		baseURL = baseURL + 'paypal.com/cgi-bin/webscr';
		$window.location.href=baseURL + encodeURI(paramRedirect);		
    };

	$scope.paiementSuccess = function () {
		if  ($rootScope.affichePopup === undefined){
			$('#bs-paiementSuccess').modal('show');
			$rootScope.affichePopup = false;
		}
	};

	switch (action) {
		case 'listPrestations':
			$scope.listPrestations();
		break;
		case 'paiementSuccess':
			$scope.paiementSuccess();
		break;
		default:
		break;
	}
	
}]);

})();
