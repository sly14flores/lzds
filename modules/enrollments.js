angular.module('enrollments-module', ['angularUtils.directives.dirPagination','bootstrap-modal','school-year','pnotify-module']).factory('enrollment', function($http,$timeout,$compile,bootstrapModal,schoolYear,pnotify) {
	
	function enrollment() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.student_enrollment = {};
			scope.student_enrollment.id = 0;
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
		
		self.form = function(scope,row) {
		
			scope.student_enrollment = {};
			scope.student_enrollment.id = 0;
			scope.details = {
				sub_total: 0,
				sub_total_str: 0,
				discount: 0,
				total: 0,
				total_str: 0
			};		
			
			scope.enrollment_fees = [];
			
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
			
		};		
		
		self.list = function(scope,row) {
		
			// scope.currentPage = 1;
			// scope.pageSize = 15;		
			
			if (row != null) {
			
				$http({
				  method: 'POST',
				  url: 'handlers/enrollments-list.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.enrollments);
					
				}, function myError(response) {
					 
				  // error
					
				});
				
			}
			
			$timeout(function() {
				$('#x_content_enrollment').html('Loading...');
				$('#x_content_enrollment').load('lists/enrollments.html',function() {
					$timeout(function() {
						$compile($('#x_content_enrollment')[0])(scope.$parent);
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
				
				angular.forEach(scope.enrollment_fees, function(item,i) {
					scope.details.sub_total += item.amount;
				});
				
				scope.details.total = scope.details.sub_total - scope.details.discount;
				scope.details.sub_total_str = formatThousandsNoRounding(scope.details.sub_total,2);
				scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
				
			}, function myError(response) {
				 
			  // error
				
			});				
			
		};
		
		self.total = function(scope) {
			if (isNaN(scope.details.discount)) return;
			scope.details.total = scope.details.sub_total - scope.details.discount;
			scope.details.total_str = formatThousandsNoRounding(scope.details.total,2);
		}
		
		self.save = function(scope) {
			
			if (validate(scope)) {
				pnotify.show('danger','Notification','Some fields are required.');
				return;
			}

			
			
		}
		
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