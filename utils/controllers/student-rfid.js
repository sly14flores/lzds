var app = angular.module('studentRfid',['student-rfid-module']);

app.controller('studentRfidCtrl',function($scope,form) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data

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