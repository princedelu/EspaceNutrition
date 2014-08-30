(function(){
"use strict";

angular.module('EspaceNutrition')
.controller('EspaceNutritionCtrl',
['$rootScope', '$scope', '$location', '$route', '$window', 'Auth','UtilisateurFactory', function($rootScope, $scope, $location, $route, $window, Auth,UtilisateurFactory) {
    
    $scope.user = Auth.user;
    $scope.userRoles = Auth.userRoles;
    $scope.accessLevels = Auth.accessLevels;

	$scope.role = "1";

	if ($location.search().token !== undefined)
	{
		$scope.token = $location.search().token;
	}else{
		$scope.confirmpassword = "value";
	}

    $scope.logout = function() {
		delete $window.sessionStorage.token;
		 $location.path('/login');
    };
    
    $scope.login = function() {
		if ($scope.token === undefined){
		    Auth.login({
		            email: $scope.email,
		            password: $scope.password,
		        },
		        function(res) {
					$window.sessionStorage.token = res;
		            $location.path('/dashboard');
		        },
		        function(err) {
		            $scope.error = err;
		        });
		}else{
			if ($scope.password != $scope.confirmpassword){
				$scope.error = 'Les mots de passe ne sont pas identiques';
			}else{
				Auth.modificationPassword({
		            email: $scope.email,
		            password: $scope.password,
					token : $scope.token
		        },
		        function(res) {
					$window.sessionStorage.token = res;
		            $location.path('/dashboard');
		        },
		        function(err) {
		            $scope.error = err;
		        });
			}
		}
    };

	$scope.go = function ( path ) {
        $window.location.href = path;
    };

	$scope.demandeChangementPassword = function () {
        $scope.success = "";
        $scope.error = "";
        if ($scope.email === undefined){
            $scope.error = 'Veuillez saisir votre email';
        }else{
            Auth.sendMailToken({
                email: $scope.email
                },
                function(res) {
                    $scope.success = 'Un mail a été envoyé à l adresse ci-dessous afin de modifier votre mot de passe';
                },
                function(err) {
                    $scope.error = err;
                });
        }
    };

	$scope.monprofilLoad = function (id) {
        $scope.success = '';
        $scope.error = '';
		Auth.get(
		        function (res) {
					$scope.email = res.EMAIL;
					$scope.nom = res.NOM;
					$scope.prenom = res.PRENOM;
					$scope.datenaissance = res.DATENAISSANCE;
					$scope.id = res.ID;
		            $scope.success = 'Succes';
					$('#dateNaissanceProfil').datepicker({format: 'dd-mm-yyyy'}); 
		            $('#bs-profil').modal('show');
		        },
		        function (err) {
		            $scope.error = err;
		        });
    };

	$scope.updateProfil = function () {
        $scope.success = '';
        $scope.error = '';

		if ($scope.password != $scope.passwordConfirm){
			$scope.error = 'Les mots de passe ne sont pas identiques';
		}else{

		    var objetValue = {};
			objetValue.email=$scope.email;
			objetValue.nom=$scope.nom;
			objetValue.password=$scope.password;
			objetValue.prenom=$scope.prenom;
			objetValue.datenaissance=$scope.datenaissance;
			objetValue.id=$scope.id;
			objetValue.profil=1;

			Auth.post(objetValue,
				function () {
					$scope.success = 'Succes';
					$('#bs-profil').modal('hide');
				},
				function (err) {
					$scope.error = err;
				});
		}
    };

	$scope.offcanvas = function (id) {
		//If window is small enough, enable sidebar push menu
		if ($(window).width() <= 992) {
			$('.row-offcanvas').toggleClass('active');
			$('.left-side').removeClass("collapse-left");
			$(".right-side").removeClass("strech");
			$('.row-offcanvas').toggleClass("relative");
		} else {
			//Else, enable content streching
			$('.left-side').toggleClass("collapse-left");
			$(".right-side").toggleClass("strech");
		}
	};


}]);

})();
