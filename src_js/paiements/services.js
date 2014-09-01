(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('PaiementFactory',['$http', function($http) {

	return {
		list: function(success, error) {
			$http.get('/api/paiements').success(success).error(error);
		}
	};
}]);

})();



