(function(){
"use strict";

angular.module('EspaceNutrition').factory('PoidsFactory',['$http', function($http) {

    return {
        list: function(dateStart,dateEnd,success, error) {
            $http.get('/api/mesurespoids/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        listMine: function(success, error) {
			$http.get('/api/mesmesurespoids').success(success).error(error);
		},
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/poids/' + id
			}).success(success).error(error);			
		},
        put: function(objet, success, error) {
			$http.put('/api/poids', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/poids', objet).success(success).error(error);
		}
    };
}]);

})();



