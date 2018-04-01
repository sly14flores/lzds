angular.module('enrollments-school-year', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year']).factory('form', function($http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI,schoolYear) {
	
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
		
		self.view = function(scope) {
			
			var onOk = function() {
				
			};
			
			bootstrapModal.box2(scope,'Student Enrollment Info','dialogs/enrollment.html',onOk,'Update');
			
		};		
		
	};

	return new form();
	
});