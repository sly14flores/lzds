angular.module('window-open-post', ['bootstrap-modal']).factory('printPost', function(bootstrapModal) {
	
	function printPost() {

		var self = this;

		self.show = function(actionUrl, params, windowName='_blank', windowFeatures='') {

			 var mapForm = document.createElement("form");
			 var milliseconds = new Date().getTime();
			 windowName = windowName+milliseconds;
				mapForm.target = windowName;
				mapForm.method = "POST";
				mapForm.action = actionUrl;

				var mapInput = document.createElement("input");
					mapInput.type = "hidden";
					mapInput.name = "params";
					mapInput.value = JSON.stringify(params);;
					mapForm.appendChild(mapInput);

				document.body.appendChild(mapForm);

				map = window.open('', windowName, windowFeatures);

			if (map) {
				mapForm.submit();
			} else {				
				var onOk = function() { };				
				bootstrapModal.notify(scope,'You must allow popups for this map to work.',onOk);
			}

		};		
	
	};
	
	return new printPost();
	
});	