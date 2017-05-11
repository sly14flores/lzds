var app = angular.module('staffs',['account-module','toggle-fullscreen','staffs-module','ui.bootstrap']);

app.controller('staffsCtrl',function($scope,fullscreen,form) {
	
	form.data($scope); // initialize data
	
	$scope.views = {};	
	
	$scope.views.title = 'Staffs';
	$scope.views.panel_title = 'Staffs List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;	

});