(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('DashboardCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','PoidsFactory','MesureFactory', function($rootScope, $scope, $location, $route, $window, Auth,PoidsFactory,MesureFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

	$scope.getDataDashBoard = function () {
        var data = [];

        MesureFactory.getLastMesure(
            function(res) {
                //Mise à jour des informations poids
                 _.each(res,function(mesure){
                    if (mesure.TYPE=='POIDS'){
                        $scope.lastPoids=mesure.POIDS;
                        $scope.lastPoidsDate='le ' + mesure.DATEMESURE;
                    }else{
                        if (mesure.TYPE=='REPAS'){
                            $scope.lastRepasDate='le ' + mesure.DATEMESURE + ' à ' + mesure.HEUREMESURE;
                        }
                    }
                });
            },
            function(err) {
                $scope.error = err;
            }
        );		

        PoidsFactory.list($scope.user.email,
            function(res) {
                _.each(res,function(poids){
                    var dateMesureTab = poids.DATEMESURE.split('-');
                    var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                    data.push({'value' : poids.POIDS, 'date' : new Date(dateMesureEn)});
                });
                $scope.dataPoids=data;
            },
            function(err) {
                $scope.error = err;
            }
        );		
	};

    switch (action) {
        case 'dashboard':
            $scope.getDataDashBoard();
        break;
        default:
        break;
    }


}]);

})();
