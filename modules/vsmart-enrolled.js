angular.module('vsmart-enrolled',['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post','module-access']).factory('vsmartEnrolled',function($rootScope,$http,$timeout,schoolYear,blockUI,pnotify) {
	
	function vsmartEnrolled() {
		
		let self = this;
		
		self.data = function() {
			
			$rootScope.formHolder = {};
			
			$rootScope.views = {};
			$rootScope.views.title = 'VSmart Enrolled';
			
			schoolYear.getSys().then(response => {
				
				$rootScope.school_years = response.data.school_years;
				
			}, response => {
				
			});
			
		};
		
		function validate(form) {

			var controls = $rootScope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {

				// if (elem.$$attr.$attr.required) $rootScope.$apply(function() { elem.$touched = elem.$invalid; });
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return $rootScope.formHolder[form].$invalid;			

		};
		
	};
	
	return new vsmartEnrolled();
	
});