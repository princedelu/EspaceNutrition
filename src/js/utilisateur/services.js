(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('UtilisateurFactory',['$http', function($http) {
	var userRoles = routingConfig.userRoles;

	return {
		list: function(success, error) {
			$http.get('/api/utilisateurs').success(success).error(error);
		},
		add: function(objet, success, error) {
			$http.post('/api/utilisateur', objet).success(success).error(error);
		},
		get: function(id, success, error) {
			$http.get('/api/utilisateur/' + id).success(success).error(error);
		},
		supprimer: function(id, success, error) {
			//using $http.delete() throws a parse error in IE8
			// $http.delete('/api/utilisateur/' + id).success(success).error(error);
			$http({
				method: 'DELETE', 
				url: '/api/utilisateur/' + id
			}).success(success).error(error);			
		},
		put: function(objet, success, error) {
			$http.put('/api/utilisateur', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/utilisateur', objet).success(success).error(error);
		},
		userRoles : userRoles
	};
}]);

})();



