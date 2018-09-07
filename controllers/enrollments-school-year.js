var app = angular.module('enrollmentsSchoolYear',['account-module','toggle-fullscreen','enrollments-school-year']);

app.controller('enrollmentsSchoolYearCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'school_year',
		privileges: {
			student_enrollment: 2,
			view_enrollment: 3,
			edit_enrollment: 4,
			print_enrollment: 5,
			delete_enrollment: "delete_enrollment",
		}
	};	
	
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