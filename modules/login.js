angular.module('login-module', []).service('loginService', function($http, $window) {
	
	this.login = function(scope) {
		
		scope.views.incorrect = false;

		if (validate(scope)) {
			scope.views.incorrect = true;			
			scope.views.msg = 'Username and password are required';			
			return;
		};
		
		$http({
		  method: 'POST',
		  url: 'handlers/login.php',
		  data: scope.account,
		  headers : {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function mySucces(response) {

			console.log(response.data);			
		
			if (response.data['login']) {
				scope.views.incorrect = false;
				
				if (!response.data['active']) {
					scope.views.incorrect = true;
					scope.views.msg = 'Account is inactive. Please contact your system administrator';
					return;
				};
				
				if (!response.data['group']) {
					scope.views.incorrect = true;
					scope.views.msg = "You don't have group privileges to access the system. Please contact your system administrator";
					return;
				};				
				
				
				$window.location.href = 'index.html';
			} else {
				scope.views.incorrect = true;
				scope.views.msg = 'Username or password incorrect';				
			};
			
		},
		function myError(response) {

		});
		
		function validate(scope) {					

			return scope.formHolder.login.$invalid;
			
		};		
		
	};	
	
});