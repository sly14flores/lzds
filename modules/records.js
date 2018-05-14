angular.module('records-module',['ui.bootstrap','bootstrap-modal','x-panel-module']).factory('records',function($http,$timeout,$compile,bootstrapModal,xPanel) {
	
	function records() {
		
		var self = this;
		
		self.data = function(scope) {

			xPanel.start('collapse-records');
			
			scope.views.records = {};			
	
			scope.data.record = {};
			scope.data.record.id = 0;
			scope.data.records = [];
	
			scope.pagination.records = {};
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.record.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.record.$invalid;
			
		};		
		
		self.list = function(scope) {

			scope.views.records.list = false;			
			
			scope.data.record = {};
			scope.data.record.id = 0;	
		
			scope.pagination.records.currentPage = 1;
			scope.pagination.records.pageSize = 15;
			scope.pagination.records.maxSize = 5;
		
			scope.views.records.panel_title = 'Records';		

			$http({
			  method: 'POST',
			  url: 'handlers/records-list.php',
			  data: {id: scope.staff_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.records);
				scope.pagination.records.filterData = scope.data.records;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content_records').html('Loading...');
			$('#x_content_records').load('lists/records.html',function() {
				$timeout(function() { $compile($('#x_content_records')[0])(scope); },100);				
			});				
			
		};
		
		self.record = function(scope,record) {

			if (record == null) {
				scope.data.record = {};
				scope.data.record.id = 0;
				scope.data.record.staff_id = scope.staff_id;
			} else {
				scope.data.record = angular.copy(leave);
				// scope.data.record.leave_date = new Date(record.leave_date);
			};
			
			var content = 'dialogs/record.html';	

			bootstrapModal.box(scope,'Add Leave',content,self.save);			
			
		};

		self.save = function(scope) {
			
			if (scope.$id > 2) scope = scope.$parent;				
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/record-save.php',
			  data: scope.data.record
			}).then(function mySucces(response) {
				
				self.list(scope);
				
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
				  url: 'handlers/record-delete.php',
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
	
	return new records();
	
});