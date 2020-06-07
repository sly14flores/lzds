var app = angular.module('students',['account-module','toggle-fullscreen','enrollments-module','records-module','students-module','excuse-letters-module']);

app.controller('studentsCtrl',function($rootScope,$scope,fullscreen,form,enrollment,records,letters) {
	
	$rootScope.module = {
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
	
	$rootScope.views = {};
	$rootScope.views.currentPage = 1;
	
	$rootScope.wrapper = {};
	
	$rootScope.school_years = [];
	$rootScope.categories = [];
	$rootScope.current_sy = {};
	
	// initialize data	
	form.data();
	enrollment.data();
	records.data($scope);
	letters.data($scope);
	
	$rootScope.views.title = 'Students';
	$rootScope.views.panel_title = 'Students List';
	
	$rootScope.fullscreen = fullscreen;
	
	form.list();

	$rootScope.form = form;
	$rootScope.enrollment = enrollment;	
	$rootScope.records = records;
	$rootScope.letters = letters;

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