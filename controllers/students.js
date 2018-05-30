var app = angular.module('students',['account-module','toggle-fullscreen','enrollments-module','records-module','students-module','excuse-letters-module']);

app.controller('studentsCtrl',function($scope,fullscreen,form,enrollment,records,letters) {
	
	$scope.views = {};

	$scope.views.currentPage = 1;
	
	// initialize data	
	form.data($scope);
	enrollment.data($scope);
	records.data($scope);
	letters.data($scope);
	
	$scope.views.title = 'Students';
	$scope.views.panel_title = 'Students List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;
	$scope.enrollment = enrollment;	
	$scope.records = records;
	$scope.letters = letters;

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