angular.module('pnotify-module',[]).factory('pnotify',function() {

	function pnotify() {
		
		var self = this;
		
		self.show = function(type,title,text) {			
			
			new PNotify({
			  title: title,
			  type: type,
			  text: text,
			  nonblock: {
				  nonblock: true
			  },
			  styling: 'bootstrap3',
			  hide: true
			});
			
		}
		
	};
	
	return new pnotify();
			
});