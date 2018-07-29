angular.module('groups-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','x-panel-module','block-ui']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,xPanel,blockUI) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.group = {};
			scope.group.id = 0;
			scope.privileges = [];

			scope.groups = [];
			
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
			
			var controls = scope.formHolder.group.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.group.$invalid;
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.panel_title = 'Group Info';			
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Group';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.group = function(scope,row) { // form
			
			blockUI.show();			
			
			scope.views.list = true;		
			
			mode(scope,row);			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/group.html',function() {		
				$compile($('#x_content')[0])(scope);
				$timeout(function() { initSwitch(); },1000);
			});
						
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;
				
				$http({
				  method: 'POST',
				  url: 'handlers/group-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.group);
					privileges(scope,row.id);
					
					blockUI.hide();
					
				}, function myError(response) {
					 
					blockUI.hide();
					
				});
				
			} else {

				privileges(scope,0);
				blockUI.hide();				
			
			};
			
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.list = function(scope) {
			
			blockUI.show();
			
			scope.views.list = false;			
			
			scope.group = {};
			scope.group.id = 0;
			scope.privileges = [];			
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;				
		
			scope.views.panel_title = 'Groups List';		

			$http({
			  method: 'POST',
			  url: 'handlers/groups-privileges-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.groups);
				scope.filterData = scope.groups;

				blockUI.hide();				
				
			}, function myError(response) {
				 
				blockUI.hide();
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/groups.html',function() {
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
			  url: 'handlers/group-save.php',
			  data: {group: scope.group, privileges: scope.privileges}
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
				  url: 'handlers/group-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this group?',onOk,function() {});						

		};

		function initSwitch() {

			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function (html) {
				var switchery = new Switchery(html, {
					color: '#26B99A'
				});
			});

		};
		
		function privileges(scope,id) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/privileges.php',
			  data: {id: id}
			}).then(function mySuccess(response) {
				
				scope.privileges = angular.copy(response.data);
				
			}, function myError(response) {
				
				//
				
			});				
			
		};
		
	};
	
	return new form();
	
});