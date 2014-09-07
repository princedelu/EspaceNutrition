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

	$scope.initFieldContact = function() {
		var value1 = Math.floor((Math.random() * 99) + 1); 
		var value2 = Math.floor((Math.random() * 99) + 1);

		$scope.minValueSlider=0;
		$scope.maxValueSlider=100;

		if (value1 > value2){
			$scope.initValueMaxWait = value1;
			$scope.initValueMinWait = value2;
		}else{
			if (value1 < value2){
				$scope.initValueMaxWait = value2;
				$scope.initValueMinWait = value1;
			}else{
				$scope.initValueMaxWait = value2 + 1;
				$scope.initValueMinWait = value1;
			}
		}
	};

	$scope.$watch('minValueSlider', function(newValue, oldValue) {
		$scope.subForm5.$setDirty();
		$scope.subForm5.$setValidity('sliderControl',false);
		if (newValue == $scope.initValueMinWait && $scope.maxValueSlider == $scope.initValueMaxWait){
			$scope.subForm5.$setValidity('sliderControl',true);
		}
	});

	$scope.$watch('maxValueSlider', function(newValue, oldValue) {
		$scope.subForm5.$setDirty();
		$scope.subForm5.$setValidity('sliderControl',false);
		if ($scope.minValueSlider == $scope.initValueMinWait && newValue == $scope.initValueMaxWait){
			$scope.subForm5.$setValidity('sliderControl',true);
		}
	});

	switch (action) {
		case 'paiementSuccess':
			$scope.paiementSuccess();
			$scope.initFieldContact();
		break;
		default:
			$scope.initFieldContact();
		break;
	}
	
}]);

})();
