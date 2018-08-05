var app = angular.module('balancesReport',['account-module','toggle-fullscreen','ui.bootstrap','balances-report-module']);

app.controller('balancesReportCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'balances_reports',
		privileges: {
			generate_report: 2,
		}
	};	
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Balances Report';
	
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