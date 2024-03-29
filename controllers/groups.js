var app = angular.module('groups',['ui.bootstrap','account-module','toggle-fullscreen','groups-module']);

app.controller('groupsCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'groups',
		privileges: {
			add_group: 2,
			view_group: 3,
			edit_group: 4,
			delete_group: "delete_group",
		}
	};	
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Groups';
	$scope.views.panel_title = 'Groups List';
	
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