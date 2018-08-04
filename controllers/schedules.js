var app = angular.module('schedules',['account-module','toggle-fullscreen','schedules-module','ui.bootstrap']);

app.controller('schedulesCtrl',function($scope,fullscreen,form) {	
	
	$scope.module = {
		id: 'schedules',
		privileges: {

		}
	};	
	
	$scope.views = {};	
	
	$scope.views.title = 'Schedules';
	$scope.views.panel_title = 'Schedules List';
	
	$scope.fullscreen = fullscreen;
	
	form.data($scope); // initialize data	
	
	form.list($scope);

	$scope.form = form;	

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