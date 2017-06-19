angular.module('enrollments-module', ['angularUtils.directives.dirPagination','bootstrap-modal']).factory('enrollment', function($http,$timeout,$compile,bootstrapModal) {
	
	function enrollment() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.enrollment = {};
			scope.enrollment.id = 0;			
			
			scope.enrollments = [];			
			
		};
		
		self.list = function(scope) {
			
			scope.currentPage = 1;
			scope.pageSize = 15;		

			$http({
			  method: 'POST',
			  url: 'handlers/enrollments-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.students);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/enrollments.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});			
			
		};
		
	};
	
	return new enrollment();
	
});