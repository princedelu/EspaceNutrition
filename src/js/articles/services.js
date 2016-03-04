(function(){
"use strict";

angular.module('EspaceNutrition').factory('ArticleFactory',['$http', function($http) {

    return {
        list: function(success, error) {
            $http.get('/api/articles').success(success).error(error);
        },
		listCategories: function(success, error) {
            $http.get('/api/categories').success(success).error(error);
        },
        supprimer: function(id, success, error) {
			$http({
				method: 'DELETE', 
				url: '/api/articles/' + id
			}).success(success).error(error);			
		},
		get: function(id, success, error) {
			$http.get('/api/articles/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/articles', objet).success(success).error(error);
		},
		post: function(objet, success, error) {
			$http.post('/api/articles', objet).success(success).error(error);
		}
    };
}]);

})();



