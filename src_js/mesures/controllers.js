(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('MesureCtrl',
['$rootScope', '$scope', '$location', '$route', '$window','PoidsFactory','UtilisateurFactory','Auth', function($rootScope, $scope, $location, $route, $window, PoidsFactory,UtilisateurFactory, Auth) {

    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;
    
    var action = "";
    if ($route !== undefined && $route.current){
        
        if ($route.current.action !== undefined){
            action = $route.current.action;
        }
    }

    $scope.changeUserMesure = function() {
      $('#mesures').fullCalendar( 'refetchEvents' );
    };

    $scope.listMesures = function () {
        $scope.success = '';
        $scope.error = '';
        $scope.loading = true;

        var allUser = {};
        allUser.email='Tous';
        allUser.id=0;
        allUser.role=0;

        $scope.usermesure=allUser;

        UtilisateurFactory.list(
	        function (res) {
	            $scope.success = 'Succes';
                var result = _.filter(res, function(user) {
                  return user.role < 2;
                });                

                result.unshift(allUser);
               
                $scope.users = result;
	        },
	        function (err) {
	            $scope.error = err;	            
	        }
        );

        $('#mesures').fullCalendar({
            eventClick: function(calEvent, jsEvent, view) {
		        $scope.id = calEvent.id;
                $scope.createPoidsLoad(calEvent.id);

            },
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
                PoidsFactory.list($scope.usermesure.email,dateStartString,dateEndString,
		        function (res) {
		            $scope.success = 'Succes';
		            var events = [];
                    _.each(res,function(mesure){
                        var dateMesureTab = mesure.DATEMESURE.split('-');
                        var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                        events.push({
                            id : mesure.ID,
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

        var allUser = {};
        allUser.email= $scope.user.email;
        allUser.id=0;
        allUser.role=0;

        $scope.usermesure=allUser;
        var result = [];
        result.push(allUser);
               
        $scope.users = result;

        UtilisateurFactory.list(
	        function (res) {
	            $scope.success = 'Succes';
                var result = _.filter(res, function(user) {
                  return user.role < 2;
                });                

                result.unshift(allUser);
               
                $scope.users = result;
	        },
	        function (err) {
	            $scope.error = err;	            
	        }
        );

        $('#mesures').fullCalendar({
            eventClick: function(calEvent, jsEvent, view) {
		        $scope.id = calEvent.id;
                $scope.createPoidsLoad(calEvent.id);
            },
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
                PoidsFactory.listMine(dateStartString,dateEndString,
		        function (res) {
		            $scope.success = 'Succes';
		            var events = [];
                    _.each(res,function(mesure){
                        var dateMesureTab = mesure.DATEMESURE.split('-');
                        var dateMesureEn=dateMesureTab[2] + '-' + dateMesureTab[1] + '-' + dateMesureTab[0];
                        events.push({
                            id : mesure.ID,
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
        $scope.userTous = 'false';
        $scope.doublon = 'false';
        $scope.abonnementinactif = 'false';

        if (id===undefined){
            $scope.dateMesure = "";
            $scope.poidsMesure = "";
            $scope.commentaireMesure ='';
		    $scope.id = "";
        }else{
            var modeSaisieMesure = "";
            if ($scope.mesures === true){
                modeSaisieMesure = "mesures";
            }else{
                if ($scope.mesmesures === true){
                    modeSaisieMesure = "mesmesures";
                }
            }
            PoidsFactory.get(id,modeSaisieMesure,
                function (res) {
                    $scope.success = 'Succes';
                    $scope.dateMesure = res.DATEMESURE;
                    $scope.poidsMesure = res.POIDS;
                    $scope.commentaireMesure = res.COMMENTAIRE;
		            $scope.id = id;
                    $scope.emailMesure = res.EMAIL;
                },
                function (err) {
                    $scope.error = err;
                    if (err == 'Doublon') {
                        $scope.doublon = 'true';
                    }
                });
        }

		$('#dateMesure').datepicker({format: 'dd-mm-yyyy',autoclose: true,weekStart:1}).on('changeDate', function(e){
            $scope.dateMesure = e.currentTarget.value;
        });
        
		$('#bs-poids').modal('show');

		
    };

    $scope.addPoids = function () {
        $scope.success = '';
        $scope.error = '';
		$scope.doublon = 'false';
        $scope.abonnementinactif = 'false';
        $scope.userTous = 'false';

        var objetValue = {};
	    objetValue.dateMesure=$scope.dateMesure;
	    objetValue.poidsMesure=$scope.poidsMesure;
	    objetValue.commentaireMesure=$scope.commentaireMesure;

        var modeSaisieMesure = "";
        if ($scope.mesures === true){
            modeSaisieMesure = "mesures";
        }else{
            if ($scope.mesmesures === true){
                modeSaisieMesure = "mesmesures";
            }
        }
        objetValue.email=$scope.usermesure.email;

        if ($scope.id === ""){
            if (objetValue.email=='Tous'){
                $scope.userTous = 'true';
            }else{
                PoidsFactory.put(objetValue,modeSaisieMesure,
	                function () {
	                    $scope.success = 'Succes';
		                $('#bs-poids').on('hidden.bs.modal', function (e) {
		                  $('#mesures').fullCalendar( 'refetchEvents' );
		                });
		                $('#bs-poids').modal('hide');
	                },
	                function (err) {
	                    $scope.error = err;
	                    if (err == 'Doublon') {
	                        $scope.doublon = 'true';
	                    }
                        if (err == 'AbonnementInactif') {
	                        $scope.abonnementinactif = 'true';
	                    }
	                });
            }
        }else{
            objetValue.id=$scope.id;
            PoidsFactory.post(objetValue,modeSaisieMesure,
	            function () {
	                $scope.success = 'Succes';
                    $('#mesures').fullCalendar( 'refetchEvents' );
		            $('#bs-poids').modal('hide');
	            },
	            function (err) {
	                $scope.error = err;
	                if (err == 'Doublon') {
	                    $scope.doublon = 'true';
	                }
                    if (err == 'AbonnementInactif') {
                        $scope.abonnementinactif = 'true';
                    }
	            });
            
        }
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
