var app = angular.module('login',['login-module']);

app.controller('loginCtrl',function($scope,loginService) {
	
	$scope.views = {};
	$scope.account = {username: '', password: ''};
	
	$scope.login = loginService.login;
	
});