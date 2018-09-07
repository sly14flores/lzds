var app = angular.module('dtr',['account-module','toggle-fullscreen','dtr-module','ui.bootstrap']);

app.controller('dtrCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'dtr_staffs',
		privileges: {
			import_dtr: 2,
			view_dtr: 3,
			re_analyze_dtr: 4,
			view_logs: 5,
			manage_logs: 6,
		}
	};	
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Staffs DTR';
	$scope.views.panel_title = '';
	
	$scope.fullscreen = fullscreen;

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