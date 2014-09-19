(function(){
"use strict";

angular.module('EspaceNutrition').factory('AbonnementFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/abonnements').success(success).error(error);
        },
        listMine: function(success, error) {
			$http.get('/api/mesabonnements').success(success).error(error);
		},
        put: function(objet, success, error) {
			$http.put('/api/abonnement', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/abonnement', objet).success(success).error(error);
		}
    };
}]);

})();



