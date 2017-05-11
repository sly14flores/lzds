angular.module('staffs-module', ['angularUtils.directives.dirPagination','bootstrap-modal']).factory('form', function($http,$timeout,$compile) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.staff = {};
			scope.staffs = [];
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.staff.$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.staff.$invalid;
			
		};
		
		self.staff = function(scope,row) { // form
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/staff.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});				
			
		};
		
		self.list = function(scope) {		

			scope.currentPage = 1;
			scope.pageSize = 15;		
		
			$http({
			  method: 'POST',
			  url: 'handlers/staffs-list.php',
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.staffs);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/staffs.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});	

		};

		self.save = function(scope) {
			
		};
		
		self.delete = function(scope) {			
			
		};
		
	};
	
	return new form();
	
});