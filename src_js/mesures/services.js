(function(){
"use strict";

angular.module('EspaceNutrition').factory('PoidsFactory',['$http', function($http) {

    return {
        list: function(email,success, error) {
            $http.get('/api/listpoids/'+email).success(success).error(error);
        },
        get: function(id,modeSaisieMesure,success, error) {
            if (modeSaisieMesure === "mesures"){
                $http.get('/api/poids/'+id).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
                    $http.get('/api/monpoids/'+id).success(success).error(error);
                }
            }
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

angular.module('EspaceNutrition').factory('RepasFactory',['$http', function($http) {

    return {
        get: function(id,modeSaisieMesure,success, error) {
            if (modeSaisieMesure === "mesures"){
                $http.get('/api/repas/'+id).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
                    $http.get('/api/monrepas/'+id).success(success).error(error);
                }
            }
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/repas/' + id
			}).success(success).error(error);			
		},
        put: function(objet,modeSaisieMesure, success, error) {
            if (modeSaisieMesure === "mesures"){
			    $http.put('/api/repas', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.put('/api/monrepas', objet).success(success).error(error);
                }
            }
		},
		post: function(objet,modeSaisieMesure, success, error) {
			if (modeSaisieMesure === "mesures"){
			    $http.post('/api/repas', objet).success(success).error(error);
            }else{
                if (modeSaisieMesure === "mesmesures"){
			        $http.post('/api/monrepas', objet).success(success).error(error);
                }
            }
		}
    };
}]);

angular.module('EspaceNutrition').factory('MesureFactory',['$http', function($http) {

    return {
        list: function(email,dateStart,dateEnd,success, error) {
            $http.get('/api/mesures/'+email+'/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        listMine: function(dateStart,dateEnd,success, error) {
            $http.get('/api/mesmesures/'+dateStart+'/'+dateEnd).success(success).error(error);
        },
        getLastMesure : function(success, error) {
            $http.get('/api/lastmesures').success(success).error(error);
        },
        listNotificationsAdmin: function(success, error) {
            $http.get('/api/notificationsAdmin').success(success).error(error);
        },
        listNotificationsUser: function(success, error) {
            $http.get('/api/notificationsUser').success(success).error(error);
        }
    };
}]);


})();



