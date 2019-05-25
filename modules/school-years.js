angular.module('school-years-module',['ui.bootstrap','bootstrap-modal','module-access']).factory('schoolYears', function($http,$timeout,$compile,bootstrapModal,access) {

	function schoolYears() {
	
		var self = this;
		
		self.data = function(scope) {
			
			scope.sy = {};
			scope.sy.id = 0;
			
			scope.school_years = [];
			
			self.list(scope);

			scope.views.invalid_school_year = false;				
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.sy.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.sy.$invalid;
			
		};		
		
		self.list = function(scope) {
			
			if (scope.$id > 2) scope = scope.$parent;

			scope.views.list = false;		
			
			scope.sy = {};
			scope.sy.id = 0;
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;
		
			scope.views.panel_title = 'School Years';		

			$http({
			  method: 'POST',
			  url: 'handlers/sy-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.school_years);
				scope.filterData = scope.school_years;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/school-years.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});				
			
		};
		
		self.form = function(scope,sy) {
			
			var title = "Add School Year";
			
			if (sy == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_sy)) return;
				scope.sy = {};
				scope.sy.id = 0;			
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_sy)) return;
				scope.sy = angular.copy(sy);
				title = "Edit School Year";
			};
			
			var content = 'dialogs/sy.html';	

			bootstrapModal.box(scope,title,content,self.save);			
			
		};
		
		self.save = function(scope) {

			if (scope.$id>2) scope = scope.$parent;
		
			scope.views.invalid_school_year = false;		
		
			if (scope.sy.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_sy)) return false;
			};
		
			if (validate(scope)) return false;
			
			if (!scope.sy.school_year.match(/([12]\d{3}-\d{2})$/)) {
				scope.views.invalid_school_year = true;
				scope.$apply();
				return false;
			};

			$http({
			  method: 'POST',
			  url: 'handlers/sy-save.php',
			  data: scope.sy
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete)) return false;
			
			var onOk = function() {					
				
				$http({
				  method: 'POST',
				  url: 'handlers/sy-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this school year?',onOk,function() {});

		};			

	};
	
	return new schoolYears();
	
});