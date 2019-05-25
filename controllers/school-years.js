var app = angular.module('schoolYears',['school-years-module','account-module','toggle-fullscreen']);

app.controller('schoolYearsCtrl', function($scope,schoolYears,fullscreen) {
	
	$scope.module = {
		id: 'school_years',
		privileges: {
			add_sy: 2,
			view_sy: 3,
			update_sy: 4,
			delete: "delete_sy",
		}
	};	
	
	$scope.views = {};
	
	$scope.formHolder = {};
	
	schoolYears.data($scope); // initialize data	
	
	$scope.views.title = 'Manage School Years';
	$scope.views.panel_title = 'School Years';
	
	$scope.fullscreen = fullscreen;	
	
	$scope.schoolYears = schoolYears;
	
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