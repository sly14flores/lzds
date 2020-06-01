var app = angular.module('dashboard',['account-module','dashboard-module','toggle-fullscreen']);

app.controller('dashboardCtrl',function($rootScope,fullscreen,dashboard) {
	
	$rootScope.module = {
		id: 'dashboard',
		privileges: {

		}
	};

	$rootScope.views = {};
	
	$rootScope.fullscreen = fullscreen;
	
	$rootScope.dashboard = dashboard;
	$rootScope.dashboard.data();
	
});