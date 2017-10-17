var app = angular.module('dtr',['account-module','toggle-fullscreen','dtr-module','ui.bootstrap']);

app.controller('dtrCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'DTRs';
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