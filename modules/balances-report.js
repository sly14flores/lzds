angular.module('balances-report-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','school-year','window-open-post','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,schoolYear,printPost,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.report = {};
			scope.report.balances = {
				school_year: {id: 0, school_year: "-"},
				level: {id: 0, description: "All"},
				section: {}
			};
			
			scope.school_years_ = [];
			
			schoolYear.get(scope);			

			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				scope.report.balances.school_year = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			scope.levels_ = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			

			// angular.element(document).ready(function () {
			
				$timeout(function() {
					
					scope.school_years_.push({id: 0, school_year: "SY"});
					
					angular.forEach(scope.school_years,function(item,i) {

						scope.school_years_.push(item);
						
					});				
					
					scope.levels_.push({id: 0, description: "-"});
					
					angular.forEach(scope.levels,function(item,i) {

						scope.levels_.push(item);
						
					});

				},1000);

			// });
			
			scope.btns = {
				ok: {
					disabled: false,					
					label: 'Save'					
				},
				cancel: {
					disabled: false,					
					label: 'Cancel'		
				}
			};				
			
		};
		
		self.levelSelected = function(scope,level) {
			
			if (level == undefined) return;
			
			scope.sections = [];
			
			scope.report.balances.section = {id: 0, description: "All"};
			
			scope.sections.push({id: 0, description: "All"});
			
			angular.forEach(level.sections,function(item,i) {

				scope.sections.push(item);

			});
			
		};		
		
		function validate(scope,form) {
			
			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder[form].$invalid;
			
		};						
		
		self.balances = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.generate_report)) return;
			
			if (scope.report.balances.school_year.id == 0) {
				pnotify.show('error','Notification','Please select school year.');
				return;				
			};
			
			if (scope.report.balances.level.id == 0) {
				pnotify.show('error','Notification','Please select level.');
				return;				
			};			
			
			printPost.show('reports/balances.php',scope.report.balances);			

		};
		
	};
	
	return new form();
	
});