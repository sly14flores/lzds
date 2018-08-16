angular.module('records-module', ['ui.bootstrap','bootstrap-modal','school-year','pnotify-module','block-ui','window-open-post','module-access']).factory('records', function($http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI,printPost,access) {
	
	function records() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views.records = {};			
	
			scope.data.record = {};
			scope.data.record.id = 0;
			scope.data.records = [];
	
			scope.pagination.records = {};
			
			scope.views.record_panel_title = '';
			
			schoolYear.get(scope);			
			schoolYear.current(scope);			
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.record.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.record.$invalid;
			
		};		
		
		self.list = function(scope,opt) {
		
			scope.views.records.list = false;	
			
			scope.data.record = {};
			scope.data.record.id = 0;	
		
			scope.pagination.records.currentPage = 1;
			scope.pagination.records.pageSize = 15;
			scope.pagination.records.maxSize = 5;				

			$http({
			  method: 'POST',
			  url: 'handlers/student-records-list.php',
			  data: {id: scope.student_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.records);
				scope.pagination.records.filterData = scope.data.records;
				$timeout(function() { scope.$apply(); }, 500);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			if (opt) {
				$('#x_content_records').html('Loading...');
				$('#x_content_records').load('lists/student-records.html',function() {				
					$compile($('#x_content_records')[0])(scope);
				});
			};
			
		};
		
		self.record = function(scope,record) {

			if (record != null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_record)) return;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_record)) return;
			};
		
			var title = 'Add Record';

			if (record == null) {
				scope.data.record = {};
				scope.data.record.id = 0;
				scope.data.record.student_id = scope.student_id;
				scope.data.record.record_sy = scope.current_sy;
			} else {
				scope.data.record = angular.copy(record);
				title = 'Edit Record Info';
			};

			var content = 'dialogs/student-record.html';

			bootstrapModal.box(scope,title,content,self.save);			
			
		};

		self.save = function(scope) {			
			
			if (scope.data.record.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_record)) return false;				
			};
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-record-save.php',
			  data: scope.data.record
			}).then(function mySucces(response) {

				self.list(scope,false);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_record)) return;
			
			var onOk = function() {		
				
				$http({
				  method: 'POST',
				  url: 'handlers/student-record-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope,false);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});

		};
		
	};	
	
	return new records();
	
});