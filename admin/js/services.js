(function(){
"use strict";

angular.module('EspaceNutrition')
.factory('Auth', ['$http','$cookieStore', function($http, $cookieStore){

    var accessLevels = routingConfig.accessLevels;
    var userRoles = routingConfig.userRoles;
    var currentUser = $cookieStore.get('user') || { username: '', role: userRoles.public };

	function changeUser(user) {
		_.extend(currentUser, user);
		$cookieStore.put('user',user)
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
            /*$http.post('/api/login', user).success(function(user){
                changeUser(user);
                success(user);
            }).error(error);*/
	    var userReturn = { "role": userRoles.user, "username": "toto" };
	    changeUser(userReturn);
            success(userReturn);
        },
        logout: function(success, error) {
            /*$http.post('/api/logout').success(function(){
                changeUser({
                    username: '',
                    role: userRoles.public
                });
                success();
            }).error(error);*/
	    var userReturn = { "role": userRoles.public, "username": "" };
	    changeUser(userReturn);
            success(userReturn);
        },
        accessLevels: accessLevels,
        userRoles: userRoles,
        user: currentUser
    };
}]);

})();



