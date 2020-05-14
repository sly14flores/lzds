angular.module('account-module', ['bootstrap-modal']).directive('dropDown', function() {

	return {
		restrict: 'A',
		template: '<li><a href="profile-settings.php"><i class="fa fa-user pull-right"></i> Profile Settings</a></li><li><a href="javascript:;" logout-account><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>'
	};

}).directive('accountProfile',function($http) {
	
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {

			$http({
			  method: 'POST',
			  url: 'handlers/account-profile.php',
			  headers : {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function mySucces(response) {

				scope.accountProfile = response.data;

			},
			function myError(response) {

			});

		}
	};
		
}).directive('logoutAccount', function($http,$window,bootstrapModal) {
	
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			
			var onOk = function() {
				
				$window.location.href = 'handlers/logout.php';
				
			};
			
			element.bind('click', function() {
					
				bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to logout?',onOk,function() {});

			});
			
		}
		
	};

});