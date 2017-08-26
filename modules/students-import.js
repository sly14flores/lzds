angular.module('students-import-module', ['bootstrap-modal','ui.bootstrap','block-ui','pnotify-module']).factory('form', function($http,$timeout,$compile,bootstrapModal,blockUI,pnotify) {
	
	function form() {
		
		Object.size = function(obj) {
			var size = 0, key;
			for (key in obj) {
				if (obj.hasOwnProperty(key)) size++;
			}
			return size;
		};	
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.import_enrollees = [];
			scope.views.import_size = Object.size(scope.views.import_enrollees);			
			
		};		
		
		self.list = function(scope) {
			
			blockUI.show("Loading records please wait...");			
			
			scope.enrollees = [];
			scope.suggest_students = [];
		
			scope.currentPage = 1;
			scope.pageSize = 30;
			scope.maxSize = 5;
		
			scope.views.panel_title = 'Students List';		

			$http({
			  method: 'POST',
			  url: 'handlers/students-import-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.enrollees);
				scope.filterData = scope.enrollees;
				scope.currentPage = scope.views.currentPage;
				blockUI.hide();
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/students-import.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};
				
		function idIsAdded(scope,id) {
			
			var exists = false;
			
			angular.forEach(scope.views.import_enrollees,function(item,i) {
				
				if (item == id) exists = true;
				
			});
			
			return exists;
			
		}
				
		self.addId = function(scope,row) {

			if (scope.$id > 2) scope = scope.$parent;
			if ((!idIsAdded(scope,row.enrollee_id)) && (row.enrollee_imported == 'No')) scope.views.import_enrollees.push(row.enrollee_id);
			row.added = true;
			scope.views.import_size = Object.size(scope.views.import_enrollees);			
			scope.views.currentPage = scope.currentPage;
			
		};
		
		self.import = function(scope) {			
			
			if (Object.size(scope.views.import_enrollees) == 0) {
				
				pnotify.show('danger','Notification','No added records to import. Please add at least one record.');
				return;
				
			};
			
			var onOk = function() {
				
				blockUI.show("Importing records...");				
				
				if (scope.$id > 2) scope = scope.$parent;	
				
				$http({
				  method: 'POST',
				  url: 'handlers/import-student.php',
				  data: {enrollees: scope.views.import_enrollees}
				}).then(function mySucces(response) {

					self.list(scope);
					self.clear(scope);
					blockUI.hide();
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to import the selected records?',onOk,function() {});

		};
		
		self.clear = function(scope) {
			
			scope.views.import_enrollees = [];
			scope.views.import_size = Object.size(scope.views.import_enrollees);
			angular.forEach(scope.enrollees,function(item,i) {
				item.added = false;
			});
			
		};
		
	};
	
	return new form();
	
});