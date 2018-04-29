angular.module('enrollments-school-year', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post']).factory('form', function($http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI,schoolYear,printPost) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views = {};
			scope.views.title = 'School Years';

			scope.views.currentPage = 1;			
			
			scope.filter = {};
			
			// School Years			
			
			schoolYear.get(scope);			

			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				scope.filter.school_year = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			//
			
			// Levels

			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			//				
			
			scope.btns = {
				edit: {
					disabled: true,
				}
			};
			
		};
		
		function validate(scope) {

			var controls = scope.formHolder.enrollment.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.enrollment.$invalid;			
			
		};		
		
		self.levelSelected = function(scope,level) {
			
			if (level == undefined) return;
			
			scope.sections = [];
			
			scope.filter.section = {id: 0, description: "All"};
			
			scope.sections.push({id: 0, description: "All"});
			
			angular.forEach(level.sections,function(item,i) {

				scope.sections.push(item);

			});
			
		};
		
		self.filter = function(scope) {
			
			list(scope);
			
		};
		
		function list(scope) {

			blockUI.show("Fetching students list please wait...");
			
			scope.currentPage = scope.views.currentPage;
			scope.pageSize = 15;
			scope.maxSize = 5;

			scope.views.panel_title = 'Enrollees for School Year: '+scope.filter.school_year.school_year;
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollees.php',
			  data: scope.filter
			}).then(function mySucces(response) {

				scope.enrollees = response.data;
				scope.filterData = scope.enrollees;
				scope.currentPage = scope.views.currentPage;				
				blockUI.hide();

			}, function myError(response) {
				 
				blockUI.hide();
				
			});					
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/enrollees.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);
			});			
		
		};
		
		self.view = function(scope,enrollee) {			
			
			scope.btns.edit.disabled = true;
			
			scope.enrollment = {};
			scope.enrollment.id = 0;
			scope.enrollment_fees = [];
			scope.benrollment_fees = [];
			scope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				total: 0,
				total_str: 0
			};			
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-view.php',
			  data: {id: enrollee.id}
			}).then(function mySucces(response) {

				angular.copy(response.data.enrollment, scope.enrollment);
				angular.copy(response.data.enrollment_fees, scope.enrollment_fees);
				angular.copy(response.data.enrollment_fees, scope.benrollment_fees);
				scope.sections_d = scope.enrollment.grade.sections;
				scope.details.discount = response.data.details.discount;
				details(scope);					

			}, function myError(response) {
				 
			  // error
				
			});			
			
			var onOk = function() {
				
				self.save(scope);
				
			};
			
			bootstrapModal.box2(scope,enrollee.fullname,'dialogs/enrollment.html',onOk,'Update');
			
		};

		self.edit = function(scope) {
			
			scope.btns.edit.disabled = !scope.btns.edit.disabled;
			
		};

		self.levelSelectedD = function(scope,level) {
			
			if (level == undefined) return;
			
			scope.sections_d = [];			
			
			scope.sections_d.push({id: 0, description: "All"});
			
			angular.forEach(level.sections,function(item,i) {

				scope.sections_d.push(item);

			});
			
		};

		self.downloadFees = function(scope,level) {
			
			if (scope.btns.edit.disabled) return;
			
			scope.sections_d = level.sections;						
			
			fees(scope);
			
		};		
		
		function fees(scope) {

			if (scope.enrollment.enrollment_school_year == undefined) {
				scope.formHolder.enrollment.enrollment_school_year.$touched = true;
				return;
			};							

			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-fees.php',
			  data: {school_year: scope.enrollment.enrollment_school_year, level: scope.enrollment.grade}
			}).then(function mySucces(response) {			

				scope.enrollment_fees = angular.copy(response.data);

				blockUI.show("Fetching fees please wait...");				
				angular.forEach(scope.benrollment_fees, function(item,i) {
					scope.enrollment_fees[i]['id'] = item['id'];
				});

				blockUI.hide();
				details(scope);

			}, function myError(response) {
				 
			  // error
				
			});				
			
		};		

		self.save = function(scope) {
			
			if (validate(scope)) {
				pnotify.show('danger','Notification','Some fields are required.');
				return;
			}	
			
			$http({
			  method: 'POST',
			  url: 'handlers/enrollment-save.php',
			  data: {student_enrollment: scope.enrollment, enrollment_fees: scope.enrollment_fees, details: scope.details}
			}).then(function mySucces(response) {
				
				if (scope.enrollment.id == 0) {
					pnotify.show('success','Notification','Student successfully enrolled.');
				} else {
					pnotify.show('success','Notification','Student enrollment info successfully updated.');				
				}
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		};		
		
		function details(scope) {

			scope.details.sub_total = 0;
			angular.forEach(scope.enrollment_fees, function(item,i) {
				scope.details.sub_total += parseFloat(item.amount);
			});
			
			scope.details.total = scope.details.sub_total - scope.details.discount;
			scope.details.sub_total_str = formatThousandsNoRounding(scope.details.sub_total,2);
			scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
		
		};
		
		self.print = function(scope,enrollment) {

			printPost.show('reports/enrollment.php',{filter:{id: enrollment.id}});
			
		};			
		
		self.total = function(scope) {
			if (isNaN(scope.details.discount)) return;
			scope.details.total = scope.details.sub_total - scope.details.discount;
			scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
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