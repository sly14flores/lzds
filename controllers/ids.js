var app = angular.module('ids',['account-module','toggle-fullscreen','ids-module']);

app.controller('idsCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'ids',
		privileges: {
			generate_ids: 2,
			print_ids: 3,
		}
	};	
	
	$scope.form = form;

	$scope.form.data($scope);
	
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