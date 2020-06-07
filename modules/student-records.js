angular.module('records-module', ['ui.bootstrap','bootstrap-modal','school-year','pnotify-module','block-ui','window-open-post','module-access']).factory('records', function($rootScope,$http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI,printPost,access) {
	
	function records() {
		
		var self = this;
		
		self.scope = {};
		
		self.data = function(scope) {
			
			self.scope = scope;
			
			$rootScope.views.records = {};			
	
			$rootScope.data.record = {};
			$rootScope.data.record.id = 0;
			$rootScope.data.records = [];
	
			$rootScope.pagination.records = {};
			
			$rootScope.views.record_panel_title = '';
			
			/*
			schoolYear.getSys().then((response) => {
				
				$rootScope.school_years = response.school_years;
				$rootScope.categories = response.categories;
				
			},(response) => {
				
			});	
			schoolYear.currentSy().then((response) => {
				
				$rootScope.current_sy = response.data;				
				
			}, (response) => {
				
			});
			*/
			
		};
		
		function validate() {
			
			console.log($rootScope);
			
			var controls = $rootScope.formHolder.record.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) {
					self.scope.$apply(function() { 
						elem.$touched = elem.$invalid;
					});
				}
									
			});

			return $rootScope.formHolder.record.$invalid;
			
		};		
		
		self.list = function(opt) {
		
			$rootScope.views.records.list = false;	
			
			$rootScope.data.record = {};
			$rootScope.data.record.id = 0;	
		
			$rootScope.pagination.records.currentPage = 1;
			$rootScope.pagination.records.pageSize = 15;
			$rootScope.pagination.records.maxSize = 5;				

			$http({
			  method: 'POST',
			  url: 'handlers/student-records-list.php',
			  data: {id: $rootScope.student_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, $rootScope.data.records);
				$rootScope.pagination.records.filterData = $rootScope.data.records;
				// $timeout(function() { scope.$apply(); }, 500);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			if (opt) {
				$('#x_content_records').html('Loading...');
				$('#x_content_records').load('lists/student-records.html',function() {				
					$compile($('#x_content_records')[0])($rootScope);
				});
			};
			
		};
		
		self.record = function(record) {

			if (record != null) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.view_record)) return;
			} else {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.add_record)) return;
			};
		
			var title = 'Add Record';

			if (record == null) {
				$rootScope.data.record = {};
				$rootScope.data.record.id = 0;
				$rootScope.data.record.student_id = $rootScope.student_id;
				$rootScope.data.record.record_sy = $rootScope.current_sy;
			} else {
				$rootScope.data.record = angular.copy(record);
				title = 'Edit Record Info';
			};

			var content = 'dialogs/student-record.html';

			bootstrapModal.box($rootScope,title,content,self.save);			
			
		};

		self.save = function() {			
			
			if ($rootScope.data.record.id > 0) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.update_record)) return false;				
			};
			
			if (validate()) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-record-save.php',
			  data: $rootScope.data.record
			}).then(function mySucces(response) {

				self.list(false);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(row) {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.delete_record)) return;
			
			var onOk = function() {		
				
				$http({
				  method: 'POST',
				  url: 'handlers/student-record-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(false);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm($rootScope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});

		};
		
	};	
	
	return new records();
	
});