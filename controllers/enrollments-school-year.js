var app = angular.module('enrollmentsSchoolYear',['account-module','toggle-fullscreen','enrollments-school-year']);

app.controller('enrollmentsSchoolYearCtrl',function($scope,fullscreen,form) {
	
	$scope.form = form;

	$scope.form.data($scope);
	
});

app.filter('pagination', function() {
	  return function(input, currentPage, pageSize) {
	    if(angular.isArray(input)) {
	      var start = (currentPage-1)*pageSize;
	      var end = currentPage*pageSize;
	      return input.slice(start, end);
	    }
	  };
});