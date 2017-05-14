angular.module('fees-module', ['angularUtils.directives.dirPagination','bootstrap-modal']).factory('form', function($http,$timeout,$compile,bootstrapModal) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.fee = {};
			scope.fee.id = 0;

			scope.fees = [];
			scope.suggest_fees = [];
			
			scope.btns = {
				ok: {
					label: 'Save'
				},
				cancel: {
					label: 'Cancel'
				}
			};
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.fee.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.fee.$invalid;
			
		};
		
		self.fee = function(scope,row) { // form
			
			scope.views.panel_title = 'Add Fee';			
			scope.btns.ok.label = 'Save';
			scope.btns.cancel.label = 'Cancel';
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/fee.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});
						
			if (row != null) {
				if (scope.$id > 2) scope = scope.$parent;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';				
				scope.views.panel_title = 'Edit Fee Info';				
				$http({
				  method: 'POST',
				  url: 'handlers/fee-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.fee);
					
				}, function myError(response) {
					 
				  // error
					
				});					
			};			
			
		};
		
		self.list = function(scope) {		

			scope.fee = {};
			scope.fee.id = 0;		
		
			scope.currentPage = 1;
			scope.pageSize = 15;		
		
			scope.views.panel_title = 'Fees List';		

			$http({
			  method: 'POST',
			  url: 'handlers/fees-list.php',
			  data: {q: scope.views.search}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.fees);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$http({
			  method: 'POST',
			  url: 'handlers/fees-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_fees);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/fees.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/fee-save.php',
			  data: scope.fee
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
				  url: 'handlers/fee-delete.php',
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