(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('MesureCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','PoidsFactory','Auth', function($rootScope, $scope, $location, $route, $window, PoidsFactory, Auth) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.listMesures = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;

        $('#mesures').fullCalendar({
            header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultView: 'agendaWeek',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: function(start, end, timezone, callback) {
                var dateStart=start._d;
                var dateStartString=dateStart.getFullYear() + '-' + (dateStart.getMonth() + 1) + '-' + dateStart.getDate();
                var dateEnd=end._d;
                var dateEndString=dateEnd.getFullYear() + '-' + (dateEnd.getMonth() + 1) + '-' + dateEnd.getDate();
                PoidsFactory.list(dateStartString,dateEndString,
		        function (res) {
		            $scope.success = 'Succes';
		            var events = [];
                    _.each(res,function(mesure){
                        var dateMesureTab = mesure.DATEMESURE.split('-');
                        var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                        events.push({
                            title: mesure.EMAIL + ' : ' + mesure.POIDS,
                            start: dateMesureEn, // will be parsed
                            backgroundColor: "#00c0ef", //Info (aqua)
                            borderColor: "#00c0ef" //Info (aqua)
                        });
                    });
                    callback(events);
		        },
		        function (err) {
		            $scope.error = err;
		        });
            }
		});
        
    };

    $scope.listMesMesures = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;
        
    };

    $scope.supprimer = function (id) {
        $scope.success = '';
        $scope.error = '';
		var retVal = confirm("Voulez vous supprimer cette mesure?");
        if (retVal === true) {
		    /*AbonnementFactory.supprimer(id,
		        function () {
		            $scope.success = 'Succes';
		            $route.reload();
		        },
		        function (err) {
		            $scope.error = err;
		            $route.reload();
		        });*/
		}
    };

    

    $scope.createPoidsLoad = function (id) {
        $scope.success = '';
        $scope.error = '';

        $scope.dateMesure = "";
        $scope.poidsMesure = "";
		$scope.id = "";

		$('#dateMesure').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.dateMesure = e.currentTarget.value;
        });
        
		$('#bs-poids').modal('show');

		
    };

    $scope.add = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        $scope.errorDate = 'false';
        $scope.pbuser = 'false';
		
    };


    switch (action) {
        case 'listMesMesures':
            $scope.listMesMesures();
        break;
        case 'listMesures':
            $scope.listMesures();
        break;
        default:
        break;
    }
    
}]);

})();
