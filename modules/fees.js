angular.module('fees-module', ['bootstrap-modal','school-year','ui.bootstrap','block-ui']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,blockUI) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;			
			
			scope.fee = {};
			scope.fee.id = 0;
			
			scope.fee_items = [];
			
			scope.fees = [];
			scope.suggest_fees = [];
			
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
			
			var controls = scope.formHolder.fee.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.fee.$invalid;
			
		};
		
		self.clone = function(scope,row) {
			
			blockUI.show();			
			
			scope.views.list = true;			
			
			scope.views.panel_title = 'Clone Fee';
			scope.btns.ok.disabled = false;	
			scope.btns.ok.label = 'Save';
			scope.btns.cancel.label = 'Cancel';
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/fee.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});

			if (scope.$id > 2) scope = scope.$parent;
			
			$http({
			  method: 'POST',
			  url: 'handlers/fee-clone.php',
			  data: {id: row.id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data['fee'], scope.fee);
				angular.copy(response.data['fee_items'], scope.fee_items);
				
				blockUI.hide();				
				
			}, function myError(response) {
				 
				blockUI.hide();
				
			});				

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			schoolYear.get(scope);
			
			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				scope.fee.school_year = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.panel_title = 'Edit Fee Info';			
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Fee';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.fee = function(scope,row) { // form
			
			blockUI.show();
			
			scope.views.list = true;			
			
			mode(scope,row);			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/fee.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});
						
			if (row != null) {
				
				if (scope.$id > 2) scope = scope.$parent;
			
				$http({
				  method: 'POST',
				  url: 'handlers/fee-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data['fee'], scope.fee);
					angular.copy(response.data['fee_items'], scope.fee_items);
					
					blockUI.hide();
					
				}, function myError(response) {
					 
					blockUI.hide();
					
				});
				
			};

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			schoolYear.get(scope);
			
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.list = function(scope,view) {		
		
			blockUI.show();
					
			scope.views.list = false;		
		
			scope.fee = {};
			scope.fee.id = 0;		
		
			scope.fee_items = [];		
			scope.fee_items_del = [];	
		
			scope.currentPage = 1;
			scope.pageSize = 25;
			scope.maxSize = 5;			
		
			scope.views.panel_title = 'Fees List';		

			$http({
			  method: 'POST',
			  url: 'handlers/fees-list.php',
			  data: scope.filter
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.fees);
				scope.filterData = scope.fees;
				
				blockUI.hide();				
				
			}, function myError(response) {
				 
				blockUI.hide();
				
			});	
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/fees.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			blockUI.show();
			
			$http({
			  method: 'POST',
			  url: 'handlers/fee-save.php',
			  data: {fee: scope.fee, fee_items: scope.fee_items, fee_items_del: scope.fee_items_del}
			}).then(function mySucces(response) {
				
				self.list(scope,'fee');
				scope.fee_items_del = [];
				
				blockUI.hide();				
				
			}, function myError(response) {
				 
				blockUI.hide();
				
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

					self.list(scope,'fee');
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});

		};
		
		self.fee_item = function(scope,row) {
			
			if (row != null) {
				row.disabled = !row.disabled;
			} else {				
				scope.fee_items.push({disabled: false, id: 0, fee_id: 0, level: {id:0,description:"",sections:[]}, amount: ''});
			}
			
		};		
		
		self.delete_fee_item = function(scope,row) {
			
			if (row.id > 0) {
				scope.fee_items_del.push(row.id);
			}			
			
			var index = scope.fee_items.indexOf(row);
			scope.fee_items.splice(index, 1);
			
		};	
		
	};
	
	return new form();
	
});