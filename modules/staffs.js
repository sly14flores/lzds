angular.module('staffs-module', []).factory('form', function($http,$timeout,$compile) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.staff = {};
			scope.staffs = [];
			
		};
		
		self.required = [];
		
		self.staff = function(scope,opt) { // form
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/staff.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});				
			
		};
		
		self.list = function(scope) {				
			
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
		
	};
	
	return new form();
	
});