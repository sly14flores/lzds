angular.module('payroll-module', ['ui.bootstrap','bootstrap-modal','school-year','window-open-post']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,printPost) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};		

			scope.months = [
				{month:"01",description:"January"},
				{month:"02",description:"February"},
				{month:"03",description:"March"},
				{month:"04",description:"April"},
				{month:"05",description:"May"},
				{month:"06",description:"June"},
				{month:"07",description:"July"},
				{month:"08",description:"August"},
				{month:"09",description:"September"},
				{month:"10",description:"October"},
				{month:"11",description:"November"},
				{month:"12",description:"December"},
			];
			
			var d = new Date();

			scope.staffDtr = {
				id: 0,
				rfid: '',
				fullname: '',
				month: scope.months[d.getMonth()],
				period: "first",
				year: d.getFullYear(),
				option: false
			};			
			
			scope.suggest_staffs = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/staffs-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_staffs);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
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
			
			var controls = scope.formHolder.payment.$$controls;

			angular.forEach(controls,function(elem,i) {

				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.payment.$invalid;
			
		};
		
		function mode(scope) {

			scope.views.panel_title = 'Payment Info';
			
		};
		
		self.payroll = function(scope,opt) {
			
			if (scope.staffDtr.id == 0) return;							
			
			var onOk = function() {
			
				scope.staffDtr.option = opt;
			
				scope.views.panel_title = scope.staffDtr.fullname+' ('+scope.staffDtr.month.description+' '+scope.staffDtr.year+')';
			
				scope.dtr = [];			
			
				$http({
				  method: 'POST',
				  url: 'handlers/dtr-staff.php',
				  data: scope.staffDtr
				}).then(function mySucces(response) {					
					
					scope.dtr = angular.copy(response.data);
					
				}, function myError(response) {
					 
				  // error
					
				});
				
				$('#x_content').html('Loading...');
				$('#x_content').load('lists/payroll-individual.html',function() {
					$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
				});		

			};
			
			if (!opt) {
				
				onOk();
				
			} else {
				
				bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to re-analyze dtr?',onOk,function() {});				
				
			}			
			
		};
		
		self.payment = function(scope,row) { // form
			
			scope.views.list = true;
			
			mode(scope);		

			$('#x_content').html('Loading...');
			$('#x_content').load('forms/payment.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});

			if (scope.$id > 2) scope = scope.$parent;

			angular.copy(row, scope.enrollment_info);
			scope.payment.enrollment_id = row.id;
			
			payments(scope,row.id);
			
		};
		
		function payments(scope,id) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/payment-edit.php',
			  data: {enrollment_id: id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.payments);
				
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		function enrollment_info(scope,id) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/payment-enrollment-info.php',
			  data: {id: id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.enrollment_info);				
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		}
		
		self.edit = function(scope,payment) {

			if (payment == null) {
				scope.payment.id = 0;
				delete scope.payment.description;
				delete scope.payment.payment_month;
				delete scope.payment.amount;
				delete scope.payment.official_receipt;
			} else {				
				scope.payment = angular.copy(payment);
			};
			
			var content = 'dialogs/payment.html';			

			bootstrapModal.box(scope,'Add Payment',content,self.save);			
			
		};		
		
		self.list = function(scope) {	
			
			scope.views.list = false;			
			
			scope.payment = {};
			scope.payment.id = 0;		
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;				
		
			scope.views.panel_title = 'Payments List';		

			$http({
			  method: 'POST',
			  url: 'handlers/payments-list.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.enrollments);
				scope.filterData = scope.enrollments;				
				
			}, function myError(response) {
				 
			  // error
				
			});					
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/payments.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {

			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/payment-save.php',
			  data: scope.payment
			}).then(function mySucces(response) {
				
				payments(scope,scope.payment.enrollment_id);				
				enrollment_info(scope,scope.payment.enrollment_id);		
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;
		
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/payment-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					payments(scope,scope.payment.enrollment_id);
					enrollment_info(scope,scope.payment.enrollment_id);					
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this payment?',onOk,function() {});

		};

		self.print = function(scope) {

			printPost.show('reports/payment.php',{filter:{id: scope.enrollment_info.id}});
			
		};		
		
	};
	
	return new form();
	
});