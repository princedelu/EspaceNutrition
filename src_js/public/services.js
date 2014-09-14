(function(){
"use strict";

	angular.module('EspaceNutrition')
	.factory('PublicFactory', ['$http','$window', function($http,$window){

		return {
			sendMessage: function(message,success, error) {
				$http.post('/api/sendMessage', message).success(function(){
		            success();
            	}).error(error);
			}
		};
	}]);

})();



