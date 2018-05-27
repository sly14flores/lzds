var app = angular.module('students',['account-module','enrollments-module','records-module','toggle-fullscreen','students-module']);

app.controller('studentsCtrl',function($scope,fullscreen,form,enrollment,records) {
	
	$scope.views = {};

	$scope.views.currentPage = 1;
	
	form.data($scope); // initialize data
	enrollment.data($scope); // initialize data	
	records.data($scope); // initialize data	
	
	$scope.views.title = 'Students';
	$scope.views.panel_title = 'Students List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;
	$scope.enrollment = enrollment;	
	$scope.records = records;	

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