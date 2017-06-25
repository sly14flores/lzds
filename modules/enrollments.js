angular.module('enrollments-module', ['angularUtils.directives.dirPagination','bootstrap-modal','school-year']).factory('enrollment', function($http,$timeout,$compile,bootstrapModal,schoolYear) {
	
	function enrollment() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.student_enrollment = {};
			scope.student_enrollment.id = 0;			
			
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
		
		self.levelSelected = function(scope,level) {

			scope.sections = level.sections;
			
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
		
	};
	
	return new enrollment();
	
});