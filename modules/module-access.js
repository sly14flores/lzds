angular.module('module-access', ['pnotify-module']).factory('access', function($http,$timeout,$compile,$q,pnotify) {
	
	function access() {
		
		var self = this;		
		
		self.has = function(scope,mod,prop) {
			
			var data = {mod: mod, prop: prop};
			
			var access = false
			
			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'handlers/access.php', false);
			
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			
			xhr.onreadystatechange = function() {
				if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
					
					var response = JSON.parse(xhr.responseText);				
					access = response.value;

					if (!response.value) {
						pnotify.show('error','Notification','Sorry, you are not allowed to use this feature.');
						access = response.value;
					};

				} else {

					pnotify.show('error','Notification','Sorry, you are not allowed to use this feature.');
					access = false;

				}
			};
			
			xhr.send(JSON.stringify(data));
			
			return access;
			
		};

	};
	
	return new access();		
	
});