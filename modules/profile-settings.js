angular.module('profile-settings-module',['module-access','pnotify-module','block-ui']).factory('userProfileSettings', function($compile,$http,$timeout,access,pnotify,blockUI) {
	
	function userProfileSettings() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views = {};
			
			scope.formHolder = {};
			
			scope.views.title = 'Profile Settings';			
			
		};
		
	};
	
	return new userProfileSettings();
	
});