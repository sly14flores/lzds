var app = angular.module('fees',['account-module','toggle-fullscreen','fees-module','ui.bootstrap']);

app.controller('feesCtrl',function($scope,fullscreen,form) {	
	
	$scope.module = {
		id: 'school_fees',
		privileges: {

		}
	};	
	
	$scope.views = {};	
	
	$scope.views.title = 'Fees';
	$scope.views.panel_title = 'Fees List';
	
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