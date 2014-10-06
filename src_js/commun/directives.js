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

var FLOAT_REGEXP = /^\-?\d+((\.|\,)\d+)?$/;
angular.module('EspaceNutrition').directive('smartFloat', function() {
  return {
    require: 'ngModel',
    link: function(scope, elm, attrs, ctrl) {
      ctrl.$parsers.unshift(function(viewValue) {
        if (FLOAT_REGEXP.test(viewValue)) {
          ctrl.$setValidity('float', true);
          return parseFloat(viewValue.replace(',', '.'));
        } else {
          ctrl.$setValidity('float', false);
          return undefined;
        }
      });
    }
  };
});


angular.module('EspaceNutrition').directive('d3CourbePoids', ['$rootScope', '$location','$window', function($rootScope, $location,$window) {
	return {
		restrict: 'E',
		replace : false,
		scope: {data: '=data'},
		link: function(scope, element, attrs) {
			var window = angular.element($window);
			var parent = angular.element(element.parent());
			var w = getParentWidth();
			var h = 450;
			
			var nbTickDateMax = Math.round(w / 120);

			var monthNames = [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
				"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ];

			var maxDataPointsForDots = 50,
				transitionDuration = 1000;

            var margin = 40;
        
        	var t = null;

            scope.render = function(data) {
                
                var svg = null,
				yAxisGroup = null,
				yAxisGroup1 = null,
				xAxisGroup = null,
				dataCirclesGroup = null,
				dataLinesGroup = null;

                if (data!==undefined){
                    d3.select("svg").remove();
                    svg = d3.select(element[0]).select('svg').select('g');
			        if (svg.empty()) {
				        svg = d3.select(element[0])
					        .append('svg:svg')
						        .attr('width', w)
						        .attr('height', h)
						        .attr('class', 'viz')
					        .append('svg:g')
						        .attr('transform', 'translate(' + margin + ',' + margin + ')');
			        }

                    var min = d3.min(data, function(d) { return parseFloat(d.value); });
                    min = min - 10;

			        var pointRadius = 4;

			        var x = d3.time.scale().range([0, w - margin * 2]).domain([data[0].date, data[data.length - 1].date]);

                    var max = d3.max(data, function(d) { return parseFloat(d.value); });
                    max = max + 10;

			        var y = d3.scale.linear().range([h - margin * 2, 0]).domain([min, max]);

                    var xAxis = d3.svg.axis().scale(x).tickSize(h - margin * 2).tickPadding(20).ticks(nbTickDateMax).tickFormat(d3.time.format("%d/%m/%Y"));
			        var yAxis = d3.svg.axis().scale(y).orient('left').tickSize(-w + margin * 2).tickPadding(10).ticks(10);

			        // y ticks and labels
			        yAxisGroup = svg.append('svg:g')
				        .attr('class', 'yTick')
				        .call(yAxis);

			        svg.append("text")
				        .attr("transform", "rotate(-90)")
				        .attr("y", 0 - margin)
				        .attr("x",40 - (h / 2))
				        .attr("dy", "1em")
				        .style("text-anchor", "middle")
				        .attr('class', 'textPoids')
				        .text("Poids");
			
			        xAxisGroup = svg.append('svg:g')
				        .attr('class', 'xTick')
				        .call(xAxis);
                    
			        // Draw the lines
			        dataLinesGroup = svg.append('svg:g');

			        var dataLines = dataLinesGroup.selectAll('.data-line')
					        .data([data]);

                    var line = d3.svg.line()
				        // assign the X function to plot our line as we wish
				        .x(function(d,i) { 
					        return x(d.date); 
				        })
				        .y(function(d) { 
					        return y(d.value); 
				        })
				        .interpolate("linear");


			        dataLines.enter().append('path')
				         .attr('class', 'data-line')
				         .style('opacity', 0.3)
				         .attr("d", line(data));


			        // Draw the points
			        dataCirclesGroup = svg.append('svg:g');

			        var circles = dataCirclesGroup.selectAll('.data-point')
				        .data(data);

			        circles
				        .enter()
					        .append('svg:circle')
						        .attr('class', 'data-point')
						        .style('opacity', 1)
						        .attr('cx', function(d) { return x(d.date); })
						        .attr('cy', function(d) { return y(d.value); })
						        .attr('r', function() { return (data.length <= maxDataPointsForDots) ? pointRadius : 0; });

			          $('svg circle').tipsy({ 
				        gravity: 'w', 
				        html: true, 
				        title: function() {
				          	var d = this.__data__;
				          	var pDate = d.date;
					        return 'Date : ' + pDate.getDate() + " " + monthNames[pDate.getMonth()] + " " + pDate.getFullYear() + '<br>Valeur : ' + d.value; 
				        }
			        });
                }
            };

            //Watch 'data' and run scope.render(newVal) whenever it changes
            //Use true for 'objectEquality' property so comparisons are done on equality and not reference
            scope.$watch('data', function(newValue, oldValue){
                scope.render(scope.data);
            }, true);  

			function returnDigit(val) { 
				var re = /\d+/;
				var digit = val.match(re)[0];
				return digit;
			} 
			
			function getParentWidth() {
                var widthParent;
                if (returnDigit(parent.css('width')) == 100){
                    widthParent=838;
                }else{
                    widthParent=returnDigit(parent.css('width'));
                }
                
				return widthParent - returnDigit(parent.css('padding-left')) - returnDigit(parent.css('padding-right'));
			}
		}

	};
}]);

})();
