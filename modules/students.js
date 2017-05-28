angular.module('students-module', ['angularUtils.directives.dirPagination','bootstrap-modal']).factory('form', function($http,$timeout,$compile,bootstrapModal) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.student = {};
			scope.student.id = 0;

			scope.students = [];
			scope.suggest_students = [];
			
			scope.parents_guardians = [];
			scope.parents_guardians_dels = [];
			
			scope.btns = {
				ok: {
					label: 'Save'
				},
				cancel: {
					label: 'Cancel'
				}
			};
			
		};
		
		function validate(scope) {

			var controls = scope.formHolder.student.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.student.$invalid;			
			
		};
		
		self.student = function(scope,row) { // form
			
			scope.student = {};
			scope.student.id = 0;
			
			scope.parents_guardians = [];
			scope.parents_guardians_dels = [];			
			
			scope.views.panel_title = 'Add Student';			
			scope.btns.ok.label = 'Save';
			scope.btns.cancel.label = 'Cancel';
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/student.html',function() {
				$timeout(function() {
					$compile($('#x_content')[0])(scope);
					scope.rows_validation = [];	
				},100);
			});
						
			if (row != null) {
				if (scope.$id > 2) scope = scope.$parent;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';				
				scope.views.panel_title = 'Edit Student Info';				
				$http({
				  method: 'POST',
				  url: 'handlers/student-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.student);
					if (scope.student.date_of_birth != null) scope.student.date_of_birth = new Date(scope.student.date_of_birth);
					
				}, function myError(response) {
					 
				  // error
					
				});					
			};					
			
		};
		
		self.list = function(scope) {		
		
			scope.currentPage = 1;
			scope.pageSize = 15;		
		
			scope.views.panel_title = 'Students List';		

			$http({
			  method: 'POST',
			  url: 'handlers/students-list.php',
			  data: {q: scope.views.search}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.students);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$http({
			  method: 'POST',
			  url: 'handlers/students-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_students);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/students.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-save.php',
			  data: {student: scope.student, parents_guardians: scope.parents_guardians, parents_guardians_dels: scope.parents_guardians_dels}
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

		self.addParent = function(scope) {
			
			scope.parents_guardians.push({
				id: 0,
				student_id: 0,
				relationship: '',
				guardian_relationship: '',
				last_name: '',
				first_name: '',
				middle_name: '',
				occupation: '',
				contact_no: ''
			});					

		};
		
		self.delParent = function(scope,row) {

			if (scope.$id > 2) scope = scope.$parent;			
			
			if (row.id > 0) {
				scope.parents_guardians_dels.push(item.child_id);
			}			

			var parents_guardians = scope.parents_guardians;
			var index = scope.parents_guardians.indexOf(row);
			scope.parents_guardians = [];				
			
			angular.forEach(parents_guardians, function(d,i) {
				
				if (index != i) {
					
					delete d['$$hashKey'];
					scope.parents_guardians.push(d);
					
				};
				
			});					
					
		};
		
	};
	
	return new form();
	
});