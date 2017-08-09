var app = angular.module('cashier',['account-module','toggle-fullscreen','cashier-module','ui.bootstrap']);

app.controller('cashierCtrl',function($scope,fullscreen,form) {
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Cashier';
	$scope.views.panel_title = 'Payments';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;	

});