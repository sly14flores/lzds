angular.module('students-module', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui']).factory('form', function($http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI) {
	
	function form() {
		
		var self = this;			
		
		self.data = function(scope) { // initialize data			
			
			scope.formHolder = {};
			
			scope.views.list = false;
			
			scope.student = {};
			scope.student.id = 0;

			scope.students = [];
			scope.suggest_students = [];
			
			scope.parents_guardians = [];
			scope.parents_guardians_dels = [];
			
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
			
			scope.data = {};
			scope.pagination = {};			
			
		};
		
		function validate(scope) {

			var controls = scope.formHolder.student.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.student.$invalid;			
			
		};
		
		function mode(scope,row) {

			if (row != null) {
				scope.views.panel_title = 'Edit Student Info'+' ('+row.fullname+')';
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Student';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};
		
		self.student = function(scope,row) { // form			
			
			scope.views.currentPage = scope.currentPage;			
			
			scope.views.list = true;				
			
			scope.student = {};
			scope.student.id = 0;
			
			scope.parents_guardians = [];
			scope.parents_guardians_dels = [];			
			
			mode(scope,row);
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/student.html',function() {
				$timeout(function() {
					$compile($('#x_content')[0])(scope);
				},100);
			});
							
			
			if (row != null) {							
			
				blockUI.show("Fetching student infos please wait...");							
			
				$http({
				  method: 'POST',
				  url: 'handlers/student-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {

					angular.copy(response.data['student'], scope.student);
					angular.copy(response.data['parents_guardians'], scope.parents_guardians);
					if (scope.student.date_of_birth != null) scope.student.date_of_birth = new Date(scope.student.date_of_birth);

					scope.student_id = row.id;

					xPanel.start('collapse-enrollments');
					xPanel.start('collapse-records');

					$timeout(function() {
						scope.enrollment.list(scope,row);
						scope.records.list(scope);
					},500);

					blockUI.hide();

				}, function myError(response) {
					 
				  // error
					
				});
				
			};					
			
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};
		
		self.list = function(scope) {

			if (scope.$id > 2) scope = scope.$parent;			
			
			scope.views.list = false;
			
			blockUI.show("Fetching students list please wait...");			
			
			scope.currentPage = scope.views.currentPage;
			scope.pageSize = 15;
			scope.maxSize = 5;			
		
			scope.views.panel_title = 'Students List';		

			$http({
			  method: 'POST',
			  url: 'handlers/students-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.students);
				scope.filterData = scope.students;
				scope.currentPage = scope.views.currentPage;				
				blockUI.hide();
				
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
			
			if (validate(scope)) {
				pnotify.show('danger','Notification','Some fields are required.');
				return;
			}						
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-save.php',
			  data: {student: scope.student, parents_guardians: scope.parents_guardians, parents_guardians_dels: scope.parents_guardians_dels}
			}).then(function mySucces(response) {
				
				scope.btns.ok.disabled = true;
				if (scope.student.id == 0) {
					pnotify.show('success','Notification','Student info successfully added.');					
					scope.student.id = response.data;
				} else {
					pnotify.show('success','Notification','Student info successfully updated.');
				}
				mode(scope,{});
				
			}, function myError(response) {
				 
			  // error
				
			});		
		
		};
		
		self.delete = function(scope,row) {
			
			scope.views.currentPage = scope.currentPage;			
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/student-delete.php',
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
				scope.parents_guardians_dels.push(row.id);
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