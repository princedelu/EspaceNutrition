(function(){
"use strict";

angular.module('EspaceNutrition')
.directive('accessLevel', ['Auth', function(Auth) {
    return {
        restrict: 'A',
        link: function($scope, element, attrs) {
            var prevDisp = element.css('display');
            var userRole;
            var accessLevel;

            $scope.user = Auth.user;
            $scope.$watch('user', function(user) {
                if(user.role)
                    userRole = user.role;
                updateCSS();
            }, true);

            attrs.$observe('accessLevel', function(al) {
                if(al) accessLevel = $scope.$eval(al);
                updateCSS();
            });

            function updateCSS() {
                if(userRole && accessLevel) {
                    if(!Auth.authorize(accessLevel, userRole)){
                        element.css('display', 'none');
                    }
                    else{
                        element.css('display', prevDisp);
                    }
                }
            }
        }
    };
}]);

angular.module('EspaceNutrition').directive('activeNav', ['$location', function($location) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var nestedA = element.find('a')[0];
            var path = nestedA.href;

            scope.location = $location;
            scope.$watch('location.absUrl()', function(newPath) {
                if (path === newPath) {
                    element.addClass('active');
                } else {
                    element.removeClass('active');
                }
            });
        }

    };

}]);

angular.module('EspaceNutrition').directive('head', ['$rootScope','$compile',
			function($rootScope, $compile){
				return {
					restrict: 'E',
					link: function(scope, elem){
						var html = '<link rel="stylesheet" ng-repeat="(routeCtrl, cssUrl) in routeStyles" ng-href="{{cssUrl}}" >';
						elem.append($compile(html)(scope));

                        scope.routeStyles = {};

						$rootScope.$on('$routeChangeStart', function (e, next, current) {

							if(current && current.$$route && current.$$route.css){
								if(!Array.isArray(current.$$route.css)){
									current.$$route.css = [current.$$route.css];
								}
								angular.forEach(current.$$route.css, function(sheet){
									delete scope.routeStyles[sheet];
								});
							}

							if(next && next.$$route && next.$$route.css){
								if(!Array.isArray(next.$$route.css)){
									next.$$route.css = [next.$$route.css];
								}
								angular.forEach(next.$$route.css, function(sheet){
									scope.routeStyles[sheet] = sheet;
								});
							}

						});

					}
				};
			}
		]);

})();
