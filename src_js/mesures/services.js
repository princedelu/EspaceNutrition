(function(){
"use strict";

angular.module('EspaceNutrition').factory('PoidsFactory',['$http', function($http) {

    return {
        get: function(id,modeSaisieMesure,success, error) {
            if (modeSaisieMesure === "mesures"){
                $http.get('/api/poids/'+id).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
                    $http.get('/api/monpoids/'+id).success(success).error(error);
                }
            }
        },
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
        put: function(objet,modeSaisieMesure, success, error) {
            if (modeSaisieMesure === "mesures"){
			    $http.put('/api/poids', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.put('/api/monpoids', objet).success(success).error(error);
                }
            }
		},
		post: function(objet,modeSaisieMesure, success, error) {
			if (modeSaisieMesure === "mesures"){
			    $http.post('/api/poids', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.post('/api/monpoids', objet).success(success).error(error);
                }
            }
		}
    };
}]);

})();



