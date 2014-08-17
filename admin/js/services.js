(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('Auth', ['$http','$window', function($http,$window){

    var accessLevels = routingConfig.accessLevels;
    var userRoles = routingConfig.userRoles;
	var publicKey=""+
"-----BEGIN PUBLIC KEY-----\n"+
"MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQBZQM9sX8M0PBHlNYO5iyHW\n"+
"/0La4UUIfLh1DlMy1lnyqlfLlRZCsyUkhzRaEAL5xrgo5qJFQvM3+CRYj4haaI4i\n"+
"GOvGe7CkdBgqGKR8EOtxHKO5lze5h474dcQodKUdK3YRpwu85fqQ8DRunTYt8O59\n"+
"+eIJhchW0tVP0LdT/x2nT9aFzxQh8g6yHT7ym4t5GrIjsapRsGZU7X0pH585HV2D\n"+
"/qpgfgnyL3sEHvN9vMRKIz+cj2JsAPu6w5s/j1hDVXvxF+C5tFYrvom9LF8C6cpQ\n"+
"PHzhI0hKAYEsV5psGqn1j1t7HA2+iSMsdPUEQqgM+IUoLaTGDFpQtgHmYi392UiB\n"+
"AgMBAAE=\n"+
"-----END PUBLIC KEY-----";

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
        login: function(user, success, error) {
            $http.post('/api/login', user).success(function(token){
				var adaptedUser = adaptUser(token.value);
                changeUser(adaptedUser);
                success(token.value);
            }).error(error);
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
			$http.get('/api/secure/utilisateurs').success(success).error(error);
		},
		add: function(nomObjet,objet, success, error) {
			$http.post('/api/secure/utilisateur', objet).success(success).error(error);
		},
		get: function(id, success, error) {
			$http.get('/api/secure/utilisateur/' + id).success(success).error(error);
		},
		delete: function(id, success, error) {
			$http.delete('/api/secure/utilisateur/' + id).success(success).error(error);
		},
		put: function(objet, success, error) {
			$http.put('/api/secure/utilisateur', objet).success(success).error(error);
		},
		userRoles : userRoles
	};
}]);

})();



