var app = angular.module('dashboard',['account-module','dashboard-module','toggle-fullscreen']);

app.controller('dashboardCtrl',function($scope,fullscreen,dashboard) {
	
	$scope.module = {
		id: 'dashboard',
		privileges: {

		}
	};

	$scope.views = {};
	
	$scope.fullscreen = fullscreen;
	
	$scope.dashboard = dashboard;
	$scope.dashboard.data($scope);
	
});