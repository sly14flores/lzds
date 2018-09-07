angular.module('travel-orders-module',['ui.bootstrap','bootstrap-modal','module-access']).factory('tos',function($http,$timeout,$compile,bootstrapModal,access) {
	
	function tos() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views.tos = {};			
	
			scope.data.to = {};
			scope.data.to.id = 0;
			scope.data.tos = [];
	
			scope.pagination.tos = {};
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.to.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.to.$invalid;
			
		};		
		
		self.list = function(scope) {

			scope.views.tos.list = false;			
			
			scope.data.to = {};
			scope.data.to.id = 0;	
		
			scope.pagination.tos.currentPage = 1;
			scope.pagination.tos.pageSize = 15;
			scope.pagination.tos.maxSize = 5;
		
			scope.views.tos.panel_title = 'Travel Orders';		

			$http({
			  method: 'POST',
			  url: 'handlers/travel-orders-list.php',
			  data: {id: scope.staff_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.tos);
				scope.pagination.tos.filterData = scope.data.tos;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content_tos').html('Loading...');
			$('#x_content_tos').load('lists/travel-orders.html',function() {
				$timeout(function() { $compile($('#x_content_tos')[0])(scope); },100);				
			});				
			
		};
		
		self.to = function(scope,to) {

			if (to == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_to)) return;
				scope.data.to = {};
				scope.data.to.id = 0;
				scope.data.to.staff_id = scope.staff_id;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_to)) return;
				scope.data.to = angular.copy(to);
				scope.data.to.to_date = new Date(to.to_date);
			};
			
			var content = 'dialogs/to.html';	

			bootstrapModal.box(scope,'Add Travel Order',content,self.save);			
			
		};

		self.save = function(scope) {
			
			if (scope.$id > 2) scope = scope.$parent;			
			
			if (scope.data.to.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_to)) return false;				
			};		
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/to-save.php',
			  data: scope.data.to
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_to)) return;
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/to-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this travel order?',onOk,function() {});

		};		
		
	};	
	
	return new tos();
	
});