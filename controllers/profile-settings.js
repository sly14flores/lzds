var app = angular.module('profileSettings',['profile-settings-module','account-module','toggle-fullscreen']);

app.controller('profileSettingsCtrl', function($scope,userProfileSettings,fullscreen) {
	
	$scope.app = userProfileSettings;
	
	$scope.app.data($scope);
	
	$scope.fullscreen = fullscreen;	
	
});