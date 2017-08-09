angular.module('cashier-module', ['angularUtils.directives.dirPagination','bootstrap-modal','school-year']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.staff = {};
			scope.staff.id = 0;

			scope.staffs = [];
			scope.suggest_staffs = [];			
			
			scope.school_years_ = [];
			
			schoolYear.get(scope);
			
			$timeout(function() {
				
				scope.school_years_.push({id: 0, school_year: "SY"});
				
				angular.forEach(scope.school_years,function(item,i) {

					scope.school_years_.push(item);
					
				});
				
			},1000);
			
			scope.filter = {
				school_year: {id: 0, school_year: "SY"}
			};			
			
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
		
		function validate(scope) {
			
			var controls = scope.formHolder.staff.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.staff.$invalid;
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.panel_title = 'Edit Staff Info';			
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Staff';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.staff = function(scope,row) { // form
			
			scope.views.list = true;		
			
			mode(scope,row);			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/staff.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});
						
			if (row != null) {
				if (scope.$id > 2) scope = scope.$parent;
				
				$http({
				  method: 'POST',
				  url: 'handlers/staff-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.staff);
					if (scope.staff.birthday != null) scope.staff.birthday = new Date(scope.staff.birthday);
					
				}, function myError(response) {
					 
				  // error
					
				});					
			};			
			
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.list = function(scope) {		
			
			scope.views.list = false;			
			
			scope.staff = {};
			scope.staff.id = 0;		
		
			scope.currentPage = 1;
			scope.pageSize = 15;		
		
			scope.views.panel_title = 'Payments List';		

			$http({
			  method: 'POST',
			  url: 'handlers/staffs-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.staffs);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$http({
			  method: 'POST',
			  url: 'handlers/staffs-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_staffs);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/staffs.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/staff-save.php',
			  data: scope.staff
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});		
		
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/staff-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});						

		};		
		
	};
	
	return new form();
	
});