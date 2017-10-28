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
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
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
			  url: 'handlers/leaves-list.php',
			  data: {id: scope.staff_id}
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
		
		self.leave = function(scope,leave) {

			if (leave == null) {
				scope.data.leave = {};
				scope.data.leave.id = 0;
				scope.data.leave.staff_id = scope.staff_id;
			} else {
				scope.data.leave = angular.copy(leave);
				scope.data.leave.leave_date = new Date(leave.leave_date);
			};
			
			var content = 'dialogs/leave.html';	

			bootstrapModal.box(scope,'Add Leave',content,self.save);			
			
		};

		self.save = function(scope) {
			
			if (scope.$id > 2) scope = scope.$parent;				
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/leave-save.php',
			  data: scope.data.leave
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/leave-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this leave?',onOk,function() {});

		};		
		
	};	
	
	return new leaves();
	
});