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
		paramRedirect=paramRedirect + "?business=admin@espace-nutrition.fr";
		paramRedirect=paramRedirect + "&item_name=Galinette cendree";
	    paramRedirect=paramRedirect + "&amount=50";
		
		paramRedirect=paramRedirect + "&cmd=_xclick";
		paramRedirect=paramRedirect + "&no_note=1";
		paramRedirect=paramRedirect + "&lc=FR";
		paramRedirect=paramRedirect + "&currency_code=EUR";
		paramRedirect=paramRedirect + "&bn=EspaceNutrition_BuyNow_WPS_FR";
		paramRedirect=paramRedirect + "&first_name=Customer's First Name"; 
		paramRedirect=paramRedirect + "&last_name=Customer's Last Name";
		paramRedirect=paramRedirect + "&payer_email=customer@example.com";
		paramRedirect=paramRedirect + "&item_number=1";
		// Append paypal return addresses
	    paramRedirect=paramRedirect + "&return=http://espace-nutrition.fr";
	    paramRedirect=paramRedirect + "&cancel_return=http://espace-nutrition.fr";
	    paramRedirect=paramRedirect + "&notify_url=http://espace-nutrition.fr";

		$window.location.href='https://www.sandbox.paypal.com/cgi-bin/webscr' + encodeURI(paramRedirect);
		

		
    };

	switch (action) {
		case 'listPrestations':
			$scope.listPrestations();
		break;
		default:
		break;
	}
	
}]);

})();
