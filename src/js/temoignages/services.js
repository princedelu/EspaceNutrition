(function(){
"use strict";

angular.module('EspaceNutrition').factory('TemoignageFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/temoignagess').success(success).error(error);
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/temoignagess/' + id
			}).success(success).error(error);			
		},
		get: function(id, success, error) {
			$http.get('/api/temoignagess/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/temoignagess', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/temoignagess', objet).success(success).error(error);
		}
    };
}]);

})();



