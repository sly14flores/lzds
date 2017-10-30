var app = angular.module('holidays',['account-module','toggle-fullscreen','holidays-module','ui.bootstrap']);

app.controller('holidaysCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};
	
	$scope.formHolder = {};
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Holidays';
	$scope.views.panel_title = 'Holidays';
	
	$scope.fullscreen = fullscreen;
	
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