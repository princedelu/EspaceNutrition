(function(){
"use strict";

angular.module('EspaceNutrition').factory('PoidsFactory',['$http', function($http) {

    return {
        list: function(email,dateStart,dateEnd,success, error) {
            $http.get('/api/mesurespoids/'+email+'/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        listMine: function(dateStart,dateEnd,success, error) {
            $http.get('/api/mesmesurespoids/'+dateStart+'/'+dateEnd).success(success).error(error);
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
        putMine: function(objet, success, error) {
			$http.put('/api/monpoids', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/poids', objet).success(success).error(error);
		},
		postMine: function(objet, success, error) {
			$http.post('/api/monpoids', objet).success(success).error(error);
		}
    };
}]);

})();



