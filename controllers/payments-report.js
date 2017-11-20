var app = angular.module('paymentsReport',['account-module','toggle-fullscreen','ui.bootstrap','payments-report-module']);

app.controller('paymentsReportCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Payments Report';
	$scope.views.panel_title = 'Staffs List';
	
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