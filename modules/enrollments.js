angular.module('enrollments-module', ['bootstrap-modal','school-year','pnotify-module','block-ui']).factory('enrollment', function($http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI) {
	
	function enrollment() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.student_enrollment = {};
			scope.bstudent_enrollment = {};
			scope.student_enrollment.id = 0;
			scope.enrollment_fees = [];			
			scope.benrollment_fees = [];			
			scope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				total: 0,
				total_str: 0
			};
			
			scope.enrollments = [];
			
			scope.enrollmentBtns = {
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

			var controls = scope.formHolder.student_enrollment.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.student_enrollment.$invalid;			
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.enrollment_panel_title = 'Edit Enrollment Info';
				scope.enrollmentBtns.ok.disabled = true;
				scope.enrollmentBtns.ok.label = 'Update';
				scope.enrollmentBtns.cancel.label = 'Close';			
			} else {
				scope.views.enrollment_panel_title = 'Enrollment Info';				
				scope.enrollmentBtns.ok.disabled = false;	
				scope.enrollmentBtns.ok.label = 'Save';
				scope.enrollmentBtns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.form = function(scope,row) {
			
			scope.student_enrollment.id = 0;
			scope.student_enrollment.school_id = '';
			scope.student_enrollment.enrollment_school_year = {id:0,school_year:""};
			scope.student_enrollment.grade = {id:0,description:"",sections:[]};
			scope.student_enrollment.section = {id:0,description:""};			
			scope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				total: 0,
				total_str: 0
			};		
			
			scope.enrollment_fees = [];
			scope.benrollment_fees = [];
			
			mode(scope,row);			
			
			$('#x_content_enrollment').html('Loading...');
			$('#x_content_enrollment').load('forms/enrollment.html',function() {
				$timeout(function() {
					$compile($('#x_content_enrollment')[0])(scope);
				},100);
			});

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				scope.student_enrollment.section = {id:0,description:""};
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			schoolYear.get(scope);

			if (row != null) {

				$http({
				  method: 'POST',
				  url: 'handlers/enrollment-view.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data.enrollment, scope.student_enrollment);
					angular.copy(response.data.enrollment, scope.bstudent_enrollment);
					angular.copy(response.data.enrollment_fees, scope.enrollment_fees);
					angular.copy(response.data.enrollment_fees, scope.benrollment_fees);
					scope.sections = scope.student_enrollment.grade.sections;
					scope.details.discount = response.data.details.discount;
					details(scope);					
					
				}, function myError(response) {
					 
				  // error
					
				});				
			
			};
			
		};		
		
		self.edit = function(scope) {
			
			scope.enrollmentBtns.ok.disabled = !scope.enrollmentBtns.ok.disabled;
			
		};		
		
		self.list = function(scope,row) {
		
			// scope.currentPage = 1;
			// scope.pageSize = 15;			
			
			if (scope.$id > 35) scope = scope.$parent;									
			
			if (row != null) {
				scope.student_enrollment.student_id = row.id;
				scope.bstudent_enrollment.student_id = row.id;
				var id = row.id;
			} else {
				var id = scope.bstudent_enrollment.student_id;
			}

				$http({
				  method: 'POST',
				  url: 'handlers/enrollments-list.php',
				  data: {id: id}
				}).then(function mySucces(response) {

					angular.copy(response.data, scope.enrollments);	
					
				}, function myError(response) {
					 
				  // error
					
				});
			
			$timeout(function() {
				$('#x_content_enrollment').html('Loading...');
				$('#x_content_enrollment').load('lists/enrollments.html',function() {
					$timeout(function() {
						$compile($('#x_content_enrollment')[0])(scope);
					},200);				
				});				
			},500);
			
		};
		
		self.levelSelected = function(scope,level) {
			
			if (level == undefined) return;
			
			scope.sections = level.sections;						
			
			fees(scope);
			
		};
		
		function fees(scope) {				
			
			if (scope.student_enrollment.enrollment_school_year == undefined) {
				scope.formHolder.student_enrollment.enrollment_school_year.$touched = true;
				return;
			};							
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-fees.php',
			  data: {school_year: scope.student_enrollment.enrollment_school_year, level: scope.student_enrollment.grade}
			}).then(function mySucces(response) {			
				
				scope.enrollment_fees = response.data;				
				if (scope.enrollmentBtns.ok.label == 'Update') {
					blockUI.show("Fetching fees please wait...");					
					angular.forEach(scope.benrollment_fees, function(item,i) {
						scope.enrollment_fees[i]['id'] = item['id'];
					});
					blockUI.hide();
				};
				details(scope);				
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		};
		
		function details(scope) {

			scope.details.sub_total = 0;
			angular.forEach(scope.enrollment_fees, function(item,i) {
				scope.details.sub_total += item.amount;
			});
			
			scope.details.total = scope.details.sub_total - scope.details.discount;
			scope.details.sub_total_str = formatThousandsNoRounding(scope.details.sub_total,2);
			scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
		
		};
		
		self.total = function(scope) {
			if (isNaN(scope.details.discount)) return;
			scope.details.total = scope.details.sub_total - scope.details.discount;
			scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
		};
		
		self.save = function(scope) {
			
			if (validate(scope)) {
				pnotify.show('danger','Notification','Some fields are required.');
				return;
			}	
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-save.php',
			  data: {student_enrollment: scope.student_enrollment, enrollment_fees: scope.enrollment_fees, details: scope.details}
			}).then(function mySucces(response) {
				
				if (scope.student_enrollment.id == 0) pnotify.show('success','Notification','Student successfully enrolled.');
				else pnotify.show('success','Notification','Student enrollment info successfully updated.');				
				mode(scope,{});
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {						

				$http({
				  method: 'POST',
				  url: 'handlers/enrollment-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope,null);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});			
			
		};
		
		function formatThousandsWithRounding(n, dp){
		  var w = n.toFixed(dp), k = w|0, b = n < 0 ? 1 : 0,
			  u = Math.abs(w-k), d = (''+u.toFixed(dp)).substr(2, dp),
			  s = ''+k, i = s.length, r = '';
		  while ( (i-=3) > b ) { r = ',' + s.substr(i, 3) + r; }
		  return s.substr(0, i + 3) + r + (d ? '.'+d: '');
		};

		var formatThousandsNoRounding = function(n, dp){
		  var e = '', s = e+n, l = s.length, b = n < 0 ? 1 : 0,
			  i = s.lastIndexOf('.'), j = i == -1 ? l : i,
			  r = e, d = s.substr(j+1, dp);
		  while ( (j-=3) > b ) { r = ',' + s.substr(j, 3) + r; }
		  return s.substr(0, j + 3) + r + 
			(dp ? '.' + d + ( d.length < dp ? 
				('00000').substr(0, dp - d.length):e):e);
		};		
		
	};
	
	return new enrollment();
	
});