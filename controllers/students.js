var app = angular.module('students',['account-module','toggle-fullscreen','students-module','ui.bootstrap']);

app.controller('studentsCtrl',function($scope,fullscreen,form) {
	
	form.data($scope); // initialize data
	
	$scope.views = {};	
	
	$scope.views.title = 'Students';
	$scope.views.panel_title = 'Students List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;	

});