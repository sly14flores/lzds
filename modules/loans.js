angular.module('loans-module',['ui.bootstrap','bootstrap-modal','module-access']).factory('loans',function($http,$timeout,$compile,bootstrapModal,access) {
	
	function loans() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views.loans = {};			
	
			scope.data.loan = {};
			scope.data.loan.id = 0;
			scope.data.loans = [];
	
			scope.pagination.loans = {};
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.loan.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.loan.$invalid;
			
		};		
		
		self.list = function(scope) {

			scope.views.loans.list = false;			
			
			scope.data.loan = {};
			scope.data.loan.id = 0;	
		
			scope.pagination.loans.currentPage = 1;
			scope.pagination.loans.pageSize = 15;
			scope.pagination.loans.maxSize = 5;
		
			scope.views.loans.panel_title = 'Loans';		

			$http({
			  method: 'POST',
			  url: 'handlers/loans-list.php',
			  data: {id: scope.staff_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.loans);
				scope.pagination.loans.filterData = scope.data.loans;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content_loans').html('Loading...');
			$('#x_content_loans').load('lists/loans.html',function() {
				$timeout(function() { $compile($('#x_content_loans')[0])(scope); },100);				
			});				
			
		};
		
		self.loan = function(scope,loan) {

			if (loan == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_loan)) return;
				scope.data.loan = {};
				scope.data.loan.id = 0;
				scope.data.loan.staff_id = scope.staff_id;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_loan)) return;				
				scope.data.loan = angular.copy(loan);
				scope.data.loan.loan_date = new Date(loan.loan_date);
				scope.data.loan.loan_effectivity = new Date(loan.loan_effectivity);
			};
			
			var content = 'dialogs/loan.html';	

			bootstrapModal.box(scope,'Add Loan',content,self.save);			
			
		};
		
		self.payments = function(scope,ln) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.view_loan_payments)) return;			
			
			var content = 'dialogs/loan-payments.html';	

			bootstrapModal.box2(scope,'Loan Payments',content,function() {});
			
			$http({
			  method: 'POST',
			  url: 'handlers/loan-payments.php',
			  data: {id: ln.id}
			}).then(function mySucces(response) {
				
				scope.loan_payments = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		self.save = function(scope) {
			
			if (scope.$id > 2) scope = scope.$parent;			
			
			if (scope.data.loan.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_loan)) return false;				
			};						
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/loan-save.php',
			  data: scope.data.loan
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_loan)) return;
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/loan-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this loan?',onOk,function() {});

		};		
		
	};	
	
	return new loans();
	
});