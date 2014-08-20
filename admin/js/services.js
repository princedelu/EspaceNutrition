(function(){
"use strict";

angular.module('Login')
.factory('Auth', ['$http','$window', function($http,$window){

    var accessLevels = routingConfig.accessLevels;
    var userRoles = routingConfig.userRoles;
	var publicKey = routingConfig.publicKey;

	var username = "";
	var role = userRoles.public;

	function verifyToken(token){

		var isValid = false;
		try {
			isValid = KJUR.jws.JWS.verify(token, publicKey);
		} catch (ex) {
			isValid = false;
		} 

		return isValid;
	}

	function adaptUser(token){
		var result =  { username: '', role: userRoles.public };
		if (token !== undefined)
		{
			if (verifyToken(token)){
				var a = token.split(".");
				var uClaim = b64utos(a[1]);
				var pClaim = KJUR.jws.JWS.readSafeJSONString(uClaim);

				var roleUser;
				if (pClaim.role == 1){
					roleUser = userRoles.user;
				}else if(pClaim.role == 2){
					roleUser = userRoles.admin;
				} else{
					roleUser = userRoles.public;
				}
				result = {	username: pClaim.username, role: roleUser };
			}
		}
		return result;
	}
	
    return {
        login: function(user, success, error) {
            $http.post('/api/login', user).success(function(token){
				var adaptedUser = adaptUser(token.value);
                success(token.value);
            }).error(error);
        },
        accessLevels: accessLevels,
        userRoles: userRoles
    };
}]);

angular.module('EspaceNutrition')
.factory('Auth', ['$http','$window', function($http,$window){

    var accessLevels = routingConfig.accessLevels;
    var userRoles = routingConfig.userRoles;
	var publicKey = routingConfig.publicKey;

    var currentUser = adaptUser($window.sessionStorage.token);
	var username = "";
	var role = userRoles.public;

	function changeUser(user) {
		_.extend(currentUser, user);
	}

	function verifyToken(token){

		var isValid = false;
		try {
			isValid = KJUR.jws.JWS.verify(token, publicKey);
		} catch (ex) {
			isValid = false;
		} 

		return isValid;
	}

	function adaptUser(token){
		var result =  { username: '', role: userRoles.public };
		if (token !== undefined)
		{
			if (verifyToken(token)){
				var a = token.split(".");
				var uClaim = b64utos(a[1]);
				var pClaim = KJUR.jws.JWS.readSafeJSONString(uClaim);

				var roleUser;
				if (pClaim.role == 1){
					roleUser = userRoles.user;
				}else if(pClaim.role == 2){
					roleUser = userRoles.admin;
				} else{
					roleUser = userRoles.public;
				}
				result = {	username: pClaim.username, role: roleUser };
			}
		}
		return result;
	}
	
    return {
        authorize: function(accessLevel, role) {
            if (accessLevel === undefined)
                accessLevel = userRoles.admin;
            if(role === undefined)
                role = currentUser.role;

            return accessLevel.bitMask & role.bitMask;
        },
        isLoggedIn: function(user) {
            if(user === undefined)
                user = currentUser;
            return user.role.title == userRoles.user.title || user.role.title == userRoles.admin.title;
        },
        logout: function(success, error) {
            $http.post('/api/logout').success(function(){
                changeUser({
                    username: '',
                    role: userRoles.public
                });
                success();
            }).error(error);
	    
        },
        accessLevels: accessLevels,
        userRoles: userRoles,
        user: currentUser
    };
}]);

angular.module('EspaceNutrition')
.factory('UtilisateurFactory',['$http', function($http) {
	var userRoles = routingConfig.userRoles;

	return {
		list: function(success, error) {
			$http.get('/api/utilisateurs').success(success).error(error);
		},
		add: function(nomObjet,objet, success, error) {
			$http.post('/api/utilisateur', objet).success(success).error(error);
		},
		get: function(id, success, error) {
			$http.get('/api/utilisateur/' + id).success(success).error(error);
		},
		delete: function(id, success, error) {
			$http.delete('/api/utilisateur/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/utilisateur', objet).success(success).error(error);
		},
		userRoles : userRoles
	};
}]);

})();



