(function(){
"use strict";

	angular.module('EspaceNutrition')
	.factory('PublicFactory', ['$http','$window', function($http,$window){

		var range;
		var rangeMin;
		var rangeMax;
		var nbTemoignagesParPages = 5;
		var uri;
		var uriRange='';
		
		return {
			listTemoignages: function(page,success, error) {
				if (page !== undefined ){
					
						rangeMin=parseInt(page)*nbTemoignagesParPages - nbTemoignagesParPages;
						rangeMax=parseInt(page)*nbTemoignagesParPages - 1;
						range=rangeMin.toString().concat("-").concat(rangeMax.toString());
						uriRange='range='+range;

					$http.get('/api/temoignages?'+uriRange).success(success).error(error);
				}else{
					$http.get('/api/temoignages').success(success).error(error);
				}
			},
			enregistrerCommande: function(id,success, error) {
				$http.put('/api/commande',id).success(success).error(error);
			},
			addTemoignage: function(objet, success, error) {
				$http.put('/api/temoignages', objet).success(success).error(error);
			},
			sendMessage: function(message,success, error) {
				$http.post('/api/sendMessage', message).success(function(){
		            success();
            	}).error(error);
			}
		};
	}]);

})();



