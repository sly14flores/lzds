angular.module('block-ui', []).factory('blockUI',function() {
	
	function blockUI() {
		
		var self = this;
		
		self.show = function(message) {
			$.blockUI({ message: '<h4><i class="fa fa-refresh"></i> '+message+'...</h4>' });
		}
		
		self.hide = function() {
			$.unblockUI();
		}
		
	};
	
	return new blockUI();
	
});