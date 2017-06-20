angular.module('enrollments-module', ['angularUtils.directives.dirPagination','bootstrap-modal']).factory('enrollment', function($http,$timeout,$compile,bootstrapModal) {
	
	function enrollment() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.enrollment = {};
			scope.enrollment.id = 0;			
			
			scope.enrollments = [];			
			
		};
		
		self.list = function(scope,row) {
			
			// scope.currentPage = 1;
			// scope.pageSize = 15;		

			$http({
			  method: 'POST',
			  url: 'handlers/enrollments-list.php',
			  data: {id: row.id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.enrollments);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$timeout(function() { 
				$('#enrollments').html('Loading...');
				$('#enrollments').load('lists/enrollments.html',function() {
					$timeout(function() { $compile($('#enrollments')[0])(scope); },200);				
				});				
			},1000);
			
		};
		
	};
	
	return new enrollment();
	
});