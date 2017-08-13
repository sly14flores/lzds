angular.module('cashier-module', ['angularUtils.directives.dirPagination','bootstrap-modal','school-year']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.payment = {};
			scope.payment.id = 0;

			scope.enrollments = [];		
			scope.payments = [];		
			
			scope.school_years_ = [];
			
			schoolYear.get(scope);
			
			$timeout(function() {
				
				scope.school_years_.push({id: 0, school_year: "SY"});
				
				angular.forEach(scope.school_years,function(item,i) {

					scope.school_years_.push(item);
					
				});
				
			},1000);
			
			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				scope.filter = {
					school_year: response.data
				};
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.payment.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.payment.$invalid;
			
		};
		
		function mode(scope) {

			scope.views.panel_title = 'Payment Info';
			
		};
		
		self.payment = function(scope,row) { // form
			
			scope.views.list = true;
			
			mode(scope);		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/payment.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});

			if (scope.$id > 2) scope = scope.$parent;
			
			$http({
			  method: 'POST',
			  url: 'handlers/payment-edit.php',
			  data: {enrollment_id: row.id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.payment);
				
			}, function myError(response) {
				 
			  // error
				
			});							
			
		};
		
		self.edit = function(scope) {
			
			var content = 'dialogs/payment.html';
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;

			};

			bootstrapModal.box(scope,'Add Payment',content,onOk);			
			
		};		
		
		self.list = function(scope) {	
			
			scope.views.list = false;			
			
			scope.payment = {};
			scope.payment.id = 0;		
		
			scope.currentPage = 1;
			scope.pageSize = 15;		
		
			scope.views.panel_title = 'Payments List';		

			$http({
			  method: 'POST',
			  url: 'handlers/payments-list.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.enrollments);
				
			}, function myError(response) {
				 
			  // error
				
			});					
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/payments.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/payment-save.php',
			  data: scope.payment
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