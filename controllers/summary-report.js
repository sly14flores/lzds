var app = angular.module('summaryReport',['account-module','toggle-fullscreen','ui.bootstrap','summary-report-module']);

app.controller('summaryReportCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Summary Reports';
	
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