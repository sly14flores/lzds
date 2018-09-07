angular.module('holidays-module', ['ui.bootstrap','bootstrap-modal','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) {				
	
			scope.holiday = {};
			scope.holiday.id = 0;

			scope.holidays = [];
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.holiday.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.holiday.$invalid;
			
		};	
		
		self.list = function(scope) {

			if (scope.$id > 2) scope = scope.$parent;		
		
			scope.views.list = false;			
			
			scope.holiday = {};
			scope.holiday.id = 0;
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;
		
			scope.views.panel_title = 'Holidays';		

			$http({
			  method: 'POST',
			  url: 'handlers/holidays-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.holidays);
				scope.filterData = scope.holidays;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/holidays.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});				
			
		};
		
		self.holiday = function(scope,holiday) {

			if (holiday == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_holiday)) return;
				scope.holiday = {};
				scope.holiday.id = 0;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_holiday)) return;
				scope.holiday = angular.copy(holiday);
				scope.holiday.holiday_date = new Date(holiday.holiday_date);
			};
			
			var content = 'dialogs/holiday.html';	

			bootstrapModal.box(scope,'Add Holiday',content,self.save);			

		};

		self.save = function(scope) {					

			if (validate(scope)) return false;
			
			if (scope.holiday.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_holiday)) return false;
			};
			
			$http({
			  method: 'POST',
			  url: 'handlers/holiday-save.php',
			  data: scope.holiday
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_holiday)) return;
			
			var onOk = function() {					
				
				$http({
				  method: 'POST',
				  url: 'handlers/holiday-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this holiday?',onOk,function() {});

		};		
		
	};	
	
	return new form();
	
});