angular.module('block-ui', []).factory('blockUI',function() {
	
	function blockUI() {
		
		var self = this;
		
		self.show = function(msg = "Please wait...") {
			
			$.blockUI({
				message: '<span style="font-size: 12px;">'+msg+'</span>',
				css: {
				border: 'none',
				padding: '15px',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .5,
				color: '#fff'
				}
			});

		};
		
		self.hide = function() {
			$.unblockUI();
		};		

	};
	
	return new blockUI();
	
});