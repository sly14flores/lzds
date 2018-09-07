var app = angular.module('login',['login-module']);

app.controller('loginCtrl',function($scope,loginService) {
	
	$scope.formHolder = {};
	$scope.views = {};
	$scope.account = {};
	
	$scope.login = loginService.login;
	
});