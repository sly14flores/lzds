angular.module('cashier-module', ['ui.bootstrap','bootstrap-modal','school-year','window-open-post']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,printPost) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.payment = {};
			scope.payment.id = 0;
			scope.payment.enrollment_id = 0;
			scope.payment.description = {name:"", description:"-"};
			scope.payment.payment_month = {no:"", name:"-"};
			
			scope.enrollment_info = {};
			
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

			scope.descriptions = [
				{name:undefined, description:"-"},
				{name:"monthly_payment", description:"Monthly Payment"},
				{name:"down_payment", description:"Down Payment"}
			];
			
			scope.getDescription = function(scope,description) {
				
				var desc = "";
				
				angular.forEach(scope.descriptions, function(item,i) {
					
					if (item.name == description) desc = item.description;
					
				});
				
				return desc;
				
			};	
			
			scope.months = [
				{no:undefined, name:"-"},
				{no:"01", name:"January"},
				{no:"02", name:"February"},
				{no:"03", name:"March"},
				{no:"04", name:"April"},
				{no:"05", name:"May"},
				{no:"06", name:"June"},
				{no:"07", name:"July"},
				{no:"08", name:"August"},
				{no:"09", name:"September"},
				{no:"10", name:"October"},
				{no:"11", name:"November"},
				{no:"12", name:"December"}
			];

			scope.getMonth = function(scope,month) {
				
				var mo = "";
				
				angular.forEach(scope.months, function(item,i) {
					
					if (item.no == month) mo = item.name;
					
				});
				
				return mo;
				
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

			scope.enrollment_info = {};
			
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

		self.soa = function(scope) {

			printPost.show('reports/soa.php',{filter:{id: scope.enrollment_info.id}});		
		
		};

	};
	
	return new form();
	
});