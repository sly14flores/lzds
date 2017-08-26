var app = angular.module('studentsImport',['account-module','toggle-fullscreen','students-import-module','ui.bootstrap']);

app.controller('studentsImportCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};	
	
	$scope.views.currentPage = 1;
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Students';
	$scope.views.panel_title = 'Students List';
	
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