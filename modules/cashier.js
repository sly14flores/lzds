angular.module('cashier-module', ['ui.bootstrap','bootstrap-modal','window-open-post','module-access','ngSanitize']).factory('form', function($http,$timeout,$compile,bootstrapModal,printPost,access) {
	
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
			
			scope.email = {};
			scope.email.enrollment = {};
			scope.email.enrollment.email = "";
			scope.email.enrollment.email_address = "";
			scope.email.enrollment.button = true;
			scope.email.enrollment.status = "";
			
			scope.email.bulk = {};
			scope.email.bulk.emails = [];
			scope.email.bulk.button = true;
			scope.email.bulk.status = "";
			scope.email.bulk.progress = 0;
			
			$http({
			  method: 'POST',
			  url: 'suggestions/cashier.php'
			}).then(function mySucces(response) {

				scope.school_years = response.data.school_years;
				scope.levels = response.data.levels;

			}, function myError(response) {
				 
			  // error
				
			});			
			
			$timeout(function() {		
			
				$http({
				  method: 'POST',
				  url: 'handlers/current-sy.php'
				}).then(function mySucces(response) {

					scope.filter.school_year = response.data;

				}, function myError(response) {
					 
				  // error
					
				});
				
				scope.filter.level = {"id":0, "description":"All"};
			
			},1000);			

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
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.enrollment_payments)) return;
			
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
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_payment)) return;
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
			
			if (scope.payment.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_payment)) return false;				
			};
		
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
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_payment)) return;			
			
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

			if (!access.has(scope,scope.module.id,scope.module.privileges.print_payment)) return;
			printPost.show('reports/payment.php',{filter:{id: scope.enrollment_info.id}});
			
		};

		self.soa = function(scope) {

			if (!access.has(scope,scope.module.id,scope.module.privileges.generate_soa)) return;
			printPost.show('reports/soa.php',{filter:{id: scope.enrollment_info.id}});		
		
		};
		
		self.email = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.send_email)) return;

			scope.email.enrollment.status = ""
			scope.email.enrollment.button = false;			
			
			bootstrapModal.box3(scope,'Email SOA for '+scope.enrollment_info.fullname,'dialogs/email.html');
			
			$http({
				method: 'GET',
				url: 'handlers/email.php',
				params: {id: scope.enrollment_info.id}
			}).then(function success(response) {
				
				scope.email.enrollment.email = response.data.content;
				scope.email.enrollment.email_address = response.data.email_address;
				scope.email.enrollment.button = false;
				
			}, function error(response) {
				
			});
			
		};
		
		self.sendEmail = function(scope) {
			
			scope.email.enrollment.status = "Sending email please wait..."
			scope.email.enrollment.button = true;
			
			$http({
				method: 'POST',
				url: 'handlers/send-email.php',
				data: {message: scope.email.enrollment.email, email_address: scope.email.enrollment.email_address}
			}).then(function success(response) {
				
				scope.email.enrollment.button = false;				
				
				if (response.data.status) {
				
					scope.email.enrollment.status = scope.email.enrollment.status+'success!';
					
				} else {
					
					scope.email.enrollment.status = scope.email.enrollment.status+'failed!';
					
				};
				
			}, function error(response) {
				
			});			
			
		};
		
		self.emailBulk = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.send_bulk_emails)) return;

			scope.email.bulk.status = "Ready to send email";
			scope.email.bulk.button = true;	
			scope.email.bulk.progress = 0;
			
			var students = (scope.filter.level.id>0)?scope.filter.level.description:'all';
			
			bootstrapModal.box3(scope,'Send bulk emails for '+students+' students, school year: '+scope.filter.school_year.school_year,'dialogs/bulk-email.html');
			
			$http({
				method: 'POST',
				url: 'handlers/emails.php',
				data: scope.filter,
			}).then(function success(response) {
				
				scope.email.bulk.emails = response.data;
				scope.email.bulk.button = false;
				
			}, function error(response) {
				
			});			
			
		};
		
		self.sendBulkEmail = function(scope,i) {
			
			scope.email.bulk.button = true;
			
			var c = i+1;			
			scope.email.bulk.status = 'Sending ('+c+'/'+scope.email.bulk.emails.length+')...';
			
			$http({
				method: 'POST',
				url: 'handlers/send-email.php',
				data: {message: scope.email.bulk.emails[i].content, email_address: scope.email.bulk.emails[i].email_address}
			}).then(function success(response) {
			
				++i;
				
				$timeout(function() {
		
					if (response.data.status) {

						scope.email.bulk.status+='succeed!';

					} else {

						scope.email.bulk.status+='failed!';

					};
		
					scope.email.bulk.progress = Math.ceil(i*100/(scope.email.bulk.emails.length));		
		
					if (i < scope.email.bulk.emails.length) {
						
						self.sendBulkEmail(scope,i);
						
					} else {
						
						$timeout(function() {
						
							scope.email.bulk.status = 'Completed';
							
						}, 500);
						
					};
	
				}, 500);			

				
			}, function error(response) {
				
			});			
			
		};

	};
	
	return new form();
	
});