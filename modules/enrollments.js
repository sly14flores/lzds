angular.module('enrollments-module', ['bootstrap-modal','school-year','pnotify-module','block-ui','window-open-post','module-access']).factory('enrollment', function($rootScope,$http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI,printPost,access) {

	function enrollment() {
		
		var self = this;
		
		self.data = function() {
			
			$rootScope.student_enrollment = {};
			$rootScope.bstudent_enrollment = {};
			$rootScope.student_enrollment.id = 0;
			$rootScope.enrollment_fees = [];			
			$rootScope.benrollment_fees = [];			
			$rootScope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				voucher: {
					enable: false,
				},
				total: 0,
				total_str: 0
			};
			
			$rootScope.enrollments = [];
			
			$rootScope.enrollmentBtns = {
				ok: {
					disabled: false,
					label: 'Save'
				},
				cancel: {
					disabled: false,					
					label: 'Cancel'
				}
			};			
			
			$rootScope.views.enrollment_panel_title = '';
			
		};
		
		function validate() {			

			var controls = $rootScope.formHolder.student_enrollment.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return $rootScope.formHolder.student_enrollment.$invalid;			
			
		};
		
		function mode(row) {
			
			if (row != null) {
				$rootScope.views.enrollment_panel_title = 'Edit Enrollment Info';
				$rootScope.enrollmentBtns.ok.disabled = true;
				$rootScope.enrollmentBtns.ok.label = 'Update';
				$rootScope.enrollmentBtns.cancel.label = 'Close';			
			} else {
				$rootScope.views.enrollment_panel_title = 'Enrollment Info';				
				$rootScope.enrollmentBtns.ok.disabled = false;	
				$rootScope.enrollmentBtns.ok.label = 'Save';
				$rootScope.enrollmentBtns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.form = function(row) {
			
			if (row != null) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.view_enrollment)) return;
			} else {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.add_enrollment)) return;
			};
			
			if ($rootScope.student_enrollment.rfid != undefined) delete $rootScope.student_enrollment.rfid;
			if ($rootScope.student_enrollment.enrollment_school_year != undefined) delete $rootScope.student_enrollment.enrollment_school_year;
			if ($rootScope.student_enrollment.grade) delete $rootScope.student_enrollment.grade;
			
			$rootScope.student_enrollment.id = 0;
			$rootScope.student_enrollment.student_id = $rootScope.student.id;
			$rootScope.student_enrollment.school_id = '';
			// $rootScope.student_enrollment.enrollment_school_year = {id:0,school_year:""};
			// $rootScope.student_enrollment.grade = {id:0,description:"",sections:[]};
			$rootScope.student_enrollment.section = {id:0,description:""};		
			$rootScope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				voucher: {
					enable: false,
				},				
				total: 0,
				total_str: 0
			};		
			
			$rootScope.enrollment_fees = [];
			$rootScope.benrollment_fees = [];
			
			mode(row);
			
			$('#x_content_enrollment').html('Loading...');
			$('#x_content_enrollment').load('forms/enrollment.html',function() {
				$timeout(function() {
					$compile($('#x_content_enrollment')[0])($rootScope);
				},100);
			});

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				$rootScope.levels = response.data;
				$rootScope.student_enrollment.section = {id:0,description:""};
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
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

			if (row != null) {
				
				$rootScope.enrollment_row = row;

				$http({
				  method: 'POST',
				  url: 'handlers/enrollment-view.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data.enrollment, $rootScope.student_enrollment);
					angular.copy(response.data.enrollment, $rootScope.bstudent_enrollment);
					angular.copy(response.data.enrollment_fees, $rootScope.enrollment_fees);
					angular.copy(response.data.enrollment_fees, $rootScope.benrollment_fees);
					$rootScope.sections = $rootScope.student_enrollment.grade.sections;
					$rootScope.details.discount = response.data.details.discount;
					$rootScope.details.voucher = response.data.details.voucher;
					details();					
					
				}, function myError(response) {
					 
				  // error
					
				});				
			
			};
			
		};		
		
		self.edit = function() {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.edit_enrollment)) return;
			
			$rootScope.enrollmentBtns.ok.disabled = !$rootScope.enrollmentBtns.ok.disabled;
			
		};		
		
		self.list = function(row) {							
			
			$rootScope.views.enrollment_panel_title = '';			
			
			if (row != null) {
				$rootScope.student_enrollment.student_id = row.id;
				$rootScope.bstudent_enrollment.student_id = row.id;
				var id = row.id;
			} else {
				var id = $rootScope.bstudent_enrollment.student_id;
			}

				$http({
				  method: 'POST',
				  url: 'handlers/enrollments-list.php',
				  data: {id: id}
				}).then(function mySucces(response) {

					angular.copy(response.data, $rootScope.enrollments);	
					
				}, function myError(response) {
					 
				  // error
					
				});
			
			$timeout(function() {
				$('#x_content_enrollment').html('Loading...');
				$('#x_content_enrollment').load('lists/enrollments.html',function() {
					$timeout(function() {
						$compile($('#x_content_enrollment')[0])($rootScope);
					},200);				
				});				
			},500);
			
		};
		
		self.levelSelected = function(level) {
			
			if (level == undefined) return;
			
			$rootScope.sections = level.sections;						
			
			fees();
			
		};
		
		function fees() {				
			
			if ($rootScope.student_enrollment.enrollment_school_year == undefined) {
				$rootScope.formHolder.student_enrollment.enrollment_school_year.$touched = true;
				return;
			};							
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-fees.php',
			  data: {school_year: $rootScope.student_enrollment.enrollment_school_year, level: $rootScope.student_enrollment.grade}
			}).then(function mySucces(response) {			
				
				$rootScope.enrollment_fees = response.data;				
				if ($rootScope.enrollmentBtns.ok.label == 'Update') {
					blockUI.show("Fetching fees please wait...");					
					angular.forEach($rootScope.benrollment_fees, function(item,i) {
						$rootScope.enrollment_fees[i]['id'] = item['id'];
					});
					blockUI.hide();
				};
				details();				
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		};
		
		function details() {

			$rootScope.details.sub_total = 0;
			angular.forEach($rootScope.enrollment_fees, function(item,i) {
				$rootScope.details.sub_total += parseFloat(item.amount);
			});
			
			$rootScope.details.total = $rootScope.details.sub_total - $rootScope.details.discount;
			if ($rootScope.details.voucher.enable) $rootScope.details.total -= $rootScope.details.voucher.amount;			
			$rootScope.details.sub_total_str = formatThousandsNoRounding($rootScope.details.sub_total,2);
			$rootScope.details.total_str = formatThousandsNoRounding($rootScope.details.total,2);
		
		};
		
		self.total = function() {
			if (isNaN($rootScope.details.discount)) return;
			if ($rootScope.details.voucher.enable) {
				if (isNaN($rootScope.details.voucher.amount)) return;
			};
			$rootScope.details.total = $rootScope.details.sub_total - $rootScope.details.discount;
			if ($rootScope.details.voucher.enable) $rootScope.details.total -= $rootScope.details.voucher.amount;
			$rootScope.details.total_str = formatThousandsNoRounding($rootScope.details.total,2);
		};
		
		self.save = function() {			
			
			if (validate()) {
				pnotify.show('danger','Notification','Some fields are required.');
				return;
			}	
			
			blockUI.show();
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-save.php',
			  data: {student_enrollment: $rootScope.student_enrollment, enrollment_fees: $rootScope.enrollment_fees, details: $rootScope.details}
			}).then(function mySucces(response) {
				
				if ($rootScope.student_enrollment.id == 0) {
					pnotify.show('success','Notification','Student successfully enrolled.');			
					$rootScope.bstudent_enrollment.student_id = $rootScope.student.id;
				} else {
					pnotify.show('success','Notification','Student enrollment info successfully updated.');				
				}
				mode({});
				
				blockUI.hide();
				
			}, function myError(response) {
				 
			  // error
				blockUI.hide();
				
			});				
			
		};
		
		self.delete = function(row) {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.delete_enrollment)) return;			
			
			var onOk = function() {						

				$http({
				  method: 'POST',
				  url: 'handlers/enrollment-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(null);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm($rootScope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});			
			
		};
		
		self.print = function() {

			printPost.show('reports/enrollment.php',{filter:{id: $rootScope.enrollment_row.id}});
			
		};

		self.voucher = function() {
			
			$rootScope.details.voucher.enable = !$rootScope.details.voucher.enable;
			
			if (!$rootScope.details.voucher.enable) {
				if ($rootScope.details.voucher.amount != undefined) delete $rootScope.details.voucher.amount;
				self.total()				
			};
			
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