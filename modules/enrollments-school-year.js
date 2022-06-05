angular.module('enrollments-school-year', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post','module-access']).factory('form', function($rootScope,$http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI,schoolYear,printPost,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function() {
			
			$rootScope.views = {};
			$rootScope.views.title = 'School Years';

			$rootScope.views.currentPage = 1;			
			
			$rootScope.filter = {};
			
			$rootScope.filter_students = {};
			$rootScope.filtered_students = [];

			// cached current school year
			$rootScope.current_sy = {};
			
			// School Years			
			
			schoolYear.get($rootScope);			

			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				$rootScope.filter.school_year = response.data;
				$rootScope.current_sy = angular.copy(response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			//
			
			// Levels

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				$rootScope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			//				
			
			$rootScope.btns = {
				edit: {
					disabled: true,
				}
			};

			$rootScope.strands = [
				{
					id: 1,
					name: 'STEM',
				},
				{
					id: 2,
					name: 'GAS',
				},
				{
					id: 3,
					name: 'ABM',
				},
				{
					id: 4,
					name: 'HUMSS',
				},
			]
			
		};
		
		function validate() {

			var controls = $rootScope.formHolder.enrollment.$$controls;
			
			angular.forEach(controls,function(elem,i) {

				// if (elem.$$attr.$attr.required) $rootScope.$apply(function() { elem.$touched = elem.$invalid; });
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return $rootScope.formHolder.enrollment.$invalid;			
			
		};		
		
		self.levelSelected = function(level) {
			
			if (level == undefined) return;
			
			$rootScope.sections = [];
			
			$rootScope.filter.section = {id: 0, description: "All"};
			
			$rootScope.sections.push({id: 0, description: "All"});
			
			angular.forEach(level.sections,function(item,i) {

				$rootScope.sections.push(item);

			});
			
		};
		
		self.filter = function() {
			
			list();
			
		};
		
		self.filterStudents = function() {
			
			$rootScope.views.search_student_ready = 'Fetching students infos please wait...';
			
			$http({
			  method: 'POST',
			  url: 'handlers/enroll-filter-students.php',
			  data: $rootScope.filter_students
			}).then(function mySucces(response) {

				$rootScope.filtered_students = angular.copy(response.data);
				$rootScope.views.search_student_ready = 'Click a student from the results to enroll';
				
			}, function myError(response) {
				 
				$rootScope.views.search_student_ready = 'Something went wrong students infos were not fetched';
				
			});				
			
		};
		
		function list() {

			blockUI.show("Fetching students list please wait...");

			// if (scope.$id > 2) scope = scope.$parent;

			$rootScope.currentPage = $rootScope.views.currentPage;
			$rootScope.pageSize = 15;
			$rootScope.maxSize = 5;

			$rootScope.views.panel_title = 'Enrollees for School Year: '+$rootScope.filter.school_year.school_year;

			$http({
			  method: 'POST',
			  url: 'handlers/enrollees.php',
			  data: $rootScope.filter
			}).then(function mySucces(response) {

				$rootScope.enrollees = response.data;
				$rootScope.filterData = $rootScope.enrollees;
				$rootScope.currentPage = $rootScope.views.currentPage;				
				blockUI.hide();

			}, function myError(response) {
				 
				blockUI.hide();
				
			});					
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/enrollees.html',function() {
				$timeout(function() { $compile($('#x_content')[0])($rootScope); },100);
			});			
		
		};
		
		function watchDetails() {
		
			$rootScope.$watch(function() {
				
				return $rootScope.details.discount;

			},function(newValue, oldValue) {

				details();

			});						
			
			$rootScope.$watch(function() {
				
				return $rootScope.details.voucher.amount;

			},function(newValue, oldValue) {

				details();

			});			

		};

		self.additionalEnrollment = function() {			
				
			var onOk = function() {
				
				// if ok
				
			};
			
			bootstrapModal.box2('','Title Here','dialogs/additional-enrollment.html',onOk);
			
		};
		
		self.view = function(enrollee) {			
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.view_enrollment)) return;			
			
			$rootScope.btns.edit.disabled = true;
			
			$rootScope.enrollment = {};
			$rootScope.enrollment.id = 0;
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
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-view.php',
			  data: {id: enrollee.id}
			}).then(function mySucces(response) {

				angular.copy(response.data.enrollment, $rootScope.enrollment);
				angular.copy(response.data.enrollment_fees, $rootScope.enrollment_fees);
				angular.copy(response.data.enrollment_fees, $rootScope.benrollment_fees);
				$rootScope.sections_d = $rootScope.enrollment.grade.sections;
				$rootScope.details.discount = response.data.details.discount;
				$rootScope.details.voucher = response.data.details.voucher;
				details();			

				watchDetails();
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			var onOk = function() {

				return self.save();
				
			};
			
			bootstrapModal.box2($rootScope,enrollee.fullname,'dialogs/enrollment.html',onOk,'Update');
			
		};

		self.enroll = function() {

			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.student_enrollment)) return;
		
			$rootScope.views.search_student_ready = '';
		
			$rootScope.enroll_school_years = [];
			$rootScope.enroll_school_years.push({id:0, school_year:"All"});

			angular.forEach($rootScope.school_years, function(item,i) {
				
				if (item.id != $rootScope.current_sy.id) $rootScope.enroll_school_years.push(item);
				
			});
			
			$rootScope.filter_students.school_year = {id:0, school_year:"All"};
			
			$rootScope.views.student = '';
			$rootScope.views.recent_sy = '';
			$rootScope.views.recent_level = '';			
			
			$rootScope.filtered_students = [];
			
			$rootScope.enrollment = {};
			$rootScope.enrollment.id = 0;
			$rootScope.enrollment.student_id = 0;
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

			$rootScope.enrollment.enrollment_school_year = angular.copy($rootScope.current_sy);
			
			watchDetails();			

			var onOk = function() {

				return enroll();

			};

			bootstrapModal.box2($rootScope,'Enroll','dialogs/enroll.html',onOk,'Submit');			

		};
		
		function enroll() {
			
			if ($rootScope.enrollment.student_id == 0) {
				pnotify.show('danger','Notification','No student selected to enroll.');
				return false;				
			};
			
			if (validate()) {
				pnotify.show('danger','Notification','Some fields are required.');
				return false;			
			}	
			
			blockUI.show("Processing please wait...");
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-save.php',
			  data: {student_enrollment: $rootScope.enrollment, enrollment_fees: $rootScope.enrollment_fees, details: $rootScope.details}
			}).then(function mySucces(response) {

				pnotify.show('success','Notification','Student successfully enrolled.');
				list();
				
				blockUI.hide();
				
				printEnroll(response.data);
				
			}, function myError(response) {
				 
			    blockUI.hide();			  
				
			});				
			
			return true;			
			
		};
		
		self.studentSelected = function(student) {
			
			$rootScope.views.student = student.fullname;
			$rootScope.views.recent_sy = student.recent_sy;
			$rootScope.views.recent_level = student.recent_level;
			
			$rootScope.enrollment.student_id = student.id;
			
		};

		self.edit = function() {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.edit_enrollment)) return;
			
			$rootScope.btns.edit.disabled = !$rootScope.btns.edit.disabled;
			
		};

		self.levelSelectedD = function(level) {

			if (level == undefined) return;
			
			$rootScope.sections_d = [];			
			
			$rootScope.sections_d.push({id: 0, description: "-"});
			
			angular.forEach(level.sections,function(item,i) {

				$rootScope.sections_d.push(item);

			});

		};

		self.downloadFees = function(level,enroll) {
			
			if ($rootScope.btns.edit.disabled && !enroll) return;			
			
			if (enroll) {
				
				if ($rootScope.enrollment.student_id == 0) {
					pnotify.show('danger','Notification','No student selected to enroll.');
					return;				
				};				
				
			};
			
			if (level === undefined) {				
				pnotify.show('danger','Notification','No level selected.');
				return;
			};
			
			$rootScope.sections_d = level.sections;						
			
			fees();
			
		};		
		
		function fees() {

			/* if (scope.enrollment.enrollment_school_year == undefined) {
				scope.formHolder.enrollment.enrollment_school_year.$touched = true;
				return;
			}; */
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-fees.php',
			  data: {school_year: $rootScope.enrollment.enrollment_school_year, level: $rootScope.enrollment.grade}
			}).then(function mySucces(response) {			

				$rootScope.enrollment_fees = angular.copy(response.data);

				blockUI.show("Fetching fees please wait...");				
				angular.forEach($rootScope.benrollment_fees, function(item,i) {
					$rootScope.enrollment_fees[i]['id'] = item['id'];
				});

				blockUI.hide();
				details();

			}, function myError(response) {
				 
			  // error
				
			});				
			
		};		

		self.save = function() {

			if ($rootScope.btns.edit.disabled) {
				pnotify.show('info','Notification','You must click edit to save changes.');
				return false;	
			}
		
			if (validate()) {
				pnotify.show('danger','Notification','Some fields are required.');
				return false;			
			}	
			
			blockUI.show("Processing please wait...");			
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-save.php',
			  data: {student_enrollment: $rootScope.enrollment, enrollment_fees: $rootScope.enrollment_fees, details: $rootScope.details}
			}).then(function mySucces(response) {
				
				if ($rootScope.enrollment.id == 0) {
					pnotify.show('success','Notification','Student successfully enrolled.');
				} else {
					pnotify.show('success','Notification','Student enrollment info successfully updated.');				
				}
				
				blockUI.hide();
				
			}, function myError(response) {
				 
				blockUI.hide();
				
			});				
			
			return true;
			
		};		
		
		self.delete = function(row) {
			
			if (!access.has($rootScope,scope.module.id,$rootScope.module.privileges.delete_enrollment)) return;			
			
			var onOk = function() {						

				$http({
				  method: 'POST',
				  url: 'handlers/enrollment-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					list();
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm($rootScope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});			
			
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
		
		self.print = function(enrollment) {

			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.print_enrollment)) return;
		
			printPost.show('reports/enrollment.php',{filter:{id: enrollment.id}});
			
		};
		
		function printEnroll(id) {

			printPost.show('reports/enrollment.php',{filter:{id: id}});		
		
		};
		
		self.voucher = function() {
			
			$rootScope.details.voucher.enable = !$rootScope.details.voucher.enable;
			
			if (!$rootScope.details.voucher.enable) {
				if ($rootScope.details.voucher.amount != undefined) delete $rootScope.details.voucher.amount;
			};
			
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

	return new form();
	
});