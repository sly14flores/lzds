var app = angular.module('profileSettings',['profile-settings-module','account-module','toggle-fullscreen']);

app.controller('profileSettingsCtrl', function($scope,userProfileSettings,fullscreen) {
	
	$scope.userProfileSettings = userProfileSettings;
	
	$scope.userProfileSettings.data($scope);
	
	$scope.fullscreen = fullscreen;	
	
});