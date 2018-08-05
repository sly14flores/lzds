angular.module('staffs-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','jspdf-module','x-panel-module','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,jspdf,xPanel,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.staff = {};
			scope.staff.id = 0;

			scope.staffs = [];
			scope.suggest_staffs = [];
			
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
			
			$http({
			  method: 'POST',
			  url: 'handlers/schedules-list.php'			 
			}).then(function mySucces(response) {
				
				scope.schedules = response.data;
				
			}, function myError(response) {
			
			  // error
			
			});	
			
			$http({
			  method: 'POST',
			  url: 'handlers/groups-list.php'			 
			}).then(function mySucces(response) {
				
				scope.groups = response.data;
				
			}, function myError(response) {
			
			  // error
			
			});			
			
			scope.staff_id = 0;
			
			scope.data = {};
			scope.pagination = {};
			
			jspdf.init();			
			
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
				scope.views.panel_title = 'Staff Info';			
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

			if (row != null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_staff)) return;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_staff)) return;
			};		
		
			scope.leaves.data(scope);
			scope.tos.data(scope);
			scope.loans.data(scope);
			scope.records.data(scope);
			
			$timeout(function() {
				scope.staff_id = (row==null)?0:row.id;
				scope.leaves.list(scope);
				scope.tos.list(scope);
				scope.loans.list(scope);
				scope.records.list(scope);
			},1000);
			
			scope.views.list = true;		
			
			mode(scope,row);			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/staff.html',function() {		
				$timeout(function() {$compile($('#x_content')[0])(scope); },100);
				$timeout(function() { initSwitch(); },500);
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
					
					xPanel.start('collapse-leaves');
					xPanel.start('collapse-tos');					
					xPanel.start('collapse-loans');					
					xPanel.start('collapse-records');
					
				}, function myError(response) {
					 
				  // error
					
				});					
			};			
			
		};
		
		self.edit = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.edit_staff)) return;
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.list = function(scope) {		
			
			scope.views.list = false;			
			
			scope.staff = {};
			scope.staff.id = 0;		
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;				
		
			scope.views.panel_title = 'Staffs List';		

			$http({
			  method: 'POST',
			  url: 'handlers/staffs-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.staffs);
				scope.filterData = scope.staffs;			
				
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
			
			if (validate(scope)) {
				pnotify.show('error','Notification','Please full up required fields');
				return;
			};
			
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
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_staff)) return;			
			
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

		function initSwitch() {

			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function (html) {
				var switchery = new Switchery(html, {
					color: '#26B99A'
				});
			});

		};
		
	};
	
	return new form();
	
});