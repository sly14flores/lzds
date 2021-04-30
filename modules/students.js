angular.module('students-module', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','module-access','school-year']).factory('form', function($rootScope,$http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI,access,schoolYear) {
	
	function form() {
		
		var self = this;			
		
		self.data = function() { // initialize data			
			
			$rootScope.formHolder = {};
			
			$rootScope.views.list = false;
			
			$rootScope.filter = {};
			
			$rootScope.student = {};
			$rootScope.student.id = 0;

			$rootScope.students = [];
			$rootScope.suggest_students = [];
			
			$rootScope.parents_guardians = [];
			$rootScope.parents_guardians_dels = [];

			$rootScope.qualifiedDiscounts = [
				{id: 1, name: 'w/Highest Honors'},
				{id: 2, name: 'w/High Honors'},
				{id: 3, name: 'w/Honors'},
				{id: 4, name: '3 Siblings: Youngest'},
				{id: 5, name: '4 - 5 Siblings: Youngest'},
			];
			
			$rootScope.btns = {
				ok: {
					disabled: false,
					label: 'Save'
				},
				cancel: {
					disabled: false,					
					label: 'Cancel'
				}
			};
			
			$rootScope.data = {};
			$rootScope.pagination = {};

			schoolYear.getSys().then((response) => {

				$rootScope.school_years = angular.copy(response.data.school_years);
				$rootScope.categories = angular.copy(response.data.categories);
				
			},(response) => {
				
			});
			schoolYear.currentSy().then((response) => {
				
				$rootScope.current_sy = response.data;				
				
			}, (response) => {
				
			});
			
		};
		
		function validate() {

			var controls = $rootScope.formHolder.student.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return $rootScope.formHolder.student.$invalid;			
			
		};
		
		function mode(row) {

			if (row != null) {
				$rootScope.views.panel_title = 'Edit Student Info'+' ('+row.fullname+')';
				$rootScope.btns.ok.disabled = true;
				$rootScope.btns.ok.label = 'Update';
				$rootScope.btns.cancel.label = 'Close';			
			} else {
				$rootScope.views.panel_title = 'Add Student';
				$rootScope.btns.ok.disabled = false;	
				$rootScope.btns.ok.label = 'Save';
				$rootScope.btns.cancel.label = 'Cancel';
			}
			
		};
		
		self.student = function(row) { // form			
			
			if (row != null) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.view_student)) return;
			} else {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.add_student)) return;
			};
			
			$rootScope.views.currentPage = $rootScope.currentPage;			
			
			$rootScope.views.list = true;				
			
			$rootScope.student = {};
			$rootScope.student.id = 0;
			
			$rootScope.parents_guardians = [];
			$rootScope.parents_guardians_dels = [];			
			
			mode(row);
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/student.html',function() {
				$timeout(function() {
					$compile($('#x_content')[0])($rootScope);
				},100);
			});
							
			
			if (row != null) {							
			
				blockUI.show("Fetching student infos please wait...");
			
				$http({
				  method: 'POST',
				  url: 'handlers/student-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data['student'], $rootScope.student);
					angular.copy(response.data['parents_guardians'], $rootScope.parents_guardians);
					if ($rootScope.student.date_of_birth != null) $rootScope.student.date_of_birth = new Date($rootScope.student.date_of_birth);

					$rootScope.student_id = row.id;

					xPanel.start('collapse-enrollments');
					xPanel.start('collapse-records');
					xPanel.start('collapse-excuse-letters');

					$timeout(function() {
						$rootScope.enrollment.list(row);
						$rootScope.records.list(true);
						$rootScope.letters.list(true);
					},500);

					blockUI.hide();

				}, function myError(response) {
					 
					blockUI.hide();
					
				});
				
			};					
			
		};
		
		self.edit = function() {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.edit_student)) return;
			
			$rootScope.btns.ok.disabled = !$rootScope.btns.ok.disabled;
			
		};
		
		self.list = function() {	
			
			$rootScope.views.list = false;
			
			blockUI.show("Fetching students list please wait...");			
			
			$rootScope.currentPage = $rootScope.views.currentPage;
			$rootScope.pageSize = 15;
			$rootScope.maxSize = 5;			
		
			$rootScope.views.panel_title = 'Students List';		

			$http({
			  method: 'POST',
			  url: 'handlers/list-students.php',
			  data: $rootScope.filter
			}).then(function mySucces(response) {
				
				angular.copy(response.data, $rootScope.students);
				$rootScope.filterData = $rootScope.students;
				$rootScope.currentPage = $rootScope.views.currentPage;				
				blockUI.hide();
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$http({
			  method: 'POST',
			  url: 'handlers/students-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, $rootScope.suggest_students);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/students.html',function() {
				$timeout(function() { $compile($('#x_content')[0])($rootScope); },100);
			});

		};

		self.save = function() {			
			
			if (validate()) {
				pnotify.show('error','Notification','Some fields are required.');
				return;
			}						
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-save.php',
			  data: {student: $rootScope.student, parents_guardians: $rootScope.parents_guardians, parents_guardians_dels: $rootScope.parents_guardians_dels}
			}).then(function mySucces(response) {

				$rootScope.btns.ok.disabled = true;
				if ($rootScope.student.id == 0) {
					pnotify.show('success','Notification','Student info successfully added.');					
					$rootScope.student.id = response.data;
				} else {
					pnotify.show('success','Notification','Student info successfully updated.');
				}
				mode({});
				
			}, function myError(response) {
				 
			  // error
				
			});		
		
		};
		
		self.delete = function(row) {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.delete_student)) return;
			
			$rootScope.views.currentPage = $rootScope.currentPage;			
			
			var onOk = function() {					
				
				$http({
				  method: 'POST',
				  url: 'handlers/student-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list();
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm($rootScope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});

		};

		self.addParent = function() {
			
			if ($rootScope.btns.ok.disabled) return;			
			
			$rootScope.parents_guardians.push({
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
		
		self.delParent = function(row) {

			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.delete_student)) return;		
			
			if ($rootScope.btns.ok.disabled) return;
			
			if (row.id > 0) {
				$rootScope.parents_guardians_dels.push(row.id);
			}			

			var parents_guardians = $rootScope.parents_guardians;
			var index = $rootScope.parents_guardians.indexOf(row);
			$rootScope.parents_guardians = [];				
			
			angular.forEach(parents_guardians, function(d,i) {
				
				if (index != i) {
					
					delete d['$$hashKey'];
					$rootScope.parents_guardians.push(d);
					
				};
				
			});					
					
		};
		
	};
	
	return new form();
	
});