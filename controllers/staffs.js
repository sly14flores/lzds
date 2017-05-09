var app = angular.module('staffs',['account-module','toggle-fullscreen','staffs-module']);

app.controller('staffsCtrl',function($scope,fullscreen,form) {
	
	form.data($scope); // initialize data
	
	$scope.views = {};	
	
	$scope.views.title = 'Staffs';
	$scope.views.panel_title = 'Manage Staffs';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;	

});