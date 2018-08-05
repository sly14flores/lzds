var app = angular.module('students',['account-module','toggle-fullscreen','enrollments-module','records-module','students-module','excuse-letters-module']);

app.controller('studentsCtrl',function($scope,fullscreen,form,enrollment,records,letters) {
	
	$scope.module = {
		id: 'students',
		privileges: {
			add_student: 2,
			view_student: 3,
			edit_student: 4,
			delete_student: "delete_student",
			add_enrollment: 5,
			view_enrollment: 6,
			edit_enrollment: 7,
			delete_enrollment: "delete_enrollment",
			add_record: 8,
			view_record: 9,
			update_record: 10,
			delete_record: "delete_record",
			add_excuse_letter: 11,
			view_excuse_letter: 12,
			update_excuse_letter: 13,
			delete_excuse_letter: "delete_excuse_letter",			
		}
	};	
	
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