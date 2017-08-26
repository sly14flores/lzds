var app = angular.module('fees',['account-module','toggle-fullscreen','fees-module','ui.bootstrap']);

app.controller('feesCtrl',function($scope,fullscreen,form) {	
	
	$scope.views = {};	
	
	$scope.views.title = 'Fees';
	$scope.views.panel_title = 'Fees List';
	
	$scope.fullscreen = fullscreen;
	
	form.data($scope); // initialize data	
	
	form.list($scope);

	$scope.form = form;	

});