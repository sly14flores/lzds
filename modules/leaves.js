angular.module('leaves-module',['ui.bootstrap','bootstrap-modal','x-panel-module']).factory('leaves',function($http,$timeout,$compile,bootstrapModal,xPanel) {
	
	function leaves() {
		
		var self = this;
		
		self.data = function(scope) {

			xPanel.start('collapse-leaves');
			
			scope.views.leaves = {};			
	
			scope.data.leave = {};
			scope.data.leave.id = 0;
			scope.data.leaves = [];
	
			scope.pagination.leaves = {};
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.leave.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.leave.$invalid;
			
		};		
		
		self.list = function(scope) {

			scope.views.leaves.list = false;			
			
			scope.data.leave = {};
			scope.data.leave.id = 0;	
		
			scope.pagination.leaves.currentPage = 1;
			scope.pagination.leaves.pageSize = 15;
			scope.pagination.leaves.maxSize = 5;
		
			scope.views.leaves.panel_title = 'Leaves';		

			$http({
			  method: 'POST',
			  url: 'handlers/leaves-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.leaves);
				scope.pagination.leaves.filterData = scope.data.leaves;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content_leaves').html('Loading...');
			$('#x_content_leaves').load('lists/leaves.html',function() {
				$timeout(function() { $compile($('#x_content_leaves')[0])(scope); },100);				
			});				
			
		};
		
	};	
	
	return new leaves();
	
});