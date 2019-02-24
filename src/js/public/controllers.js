(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionPublicCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','PublicFactory', function($rootScope, $scope, $location, $route, $window,PublicFactory) {
    
	var action = "";
	$scope.ph_numbr = /^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$/;
	
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
		
		var objetValue = {};
		objetValue.ref=$scope.idPaiement;
		objetValue.nom=$scope.nom;
		objetValue.prenom=$scope.prenom;
		objetValue.telephone=$scope.telephone;
		objetValue.email=$scope.email;
		objetValue.adresse=$scope.adresse;
		objetValue.moyen=$scope.moyen;
		objetValue.acceptation=$scope.acceptation;		

		PublicFactory.enregistrerCommande(objetValue,
			function (res) {
				$scope.loading = false;
				$('#bs-afficheMoyenPaiement').modal('show');
			},
			function (err) {
				$scope.error = "Impossible d enregistrer la commande";
				$scope.loading = false;
			}
		);		
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
	
	$scope.affichePopupMentionsLegales = function (id) {
		$('#bs-ml').modal('show');
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
