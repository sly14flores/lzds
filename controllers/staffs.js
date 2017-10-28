var app = angular.module('staffs',['account-module','toggle-fullscreen','staffs-module','leaves-module','travel-orders-module','loans-module','ui.bootstrap']);

app.controller('staffsCtrl',function($scope,fullscreen,form,leaves,tos,loans) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Staffs';
	$scope.views.panel_title = 'Staffs List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;
	
	$scope.leaves = leaves;
	$scope.tos = tos;
	$scope.loans = loans;

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