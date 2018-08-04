var app = angular.module('levels',['account-module','toggle-fullscreen','levels-module','ui.bootstrap']);

app.controller('levelsCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'levels',
		privileges: {

		}
	};	
	
	$scope.views = {};
	
	$scope.formHolder = {};
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Grade Levels/Sections';
	$scope.views.panel_title = 'Grade Levels';
	
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