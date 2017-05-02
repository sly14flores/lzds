var app = angular.module('dashboard',['account-module','toggle-fullscreen']);

app.controller('dashboardCtrl',function($scope,fullscreen) {
	
	$scope.views = {};
	
	$scope.fullscreen = fullscreen;
	
});