angular.module('ids-module', ['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,xPanel,pnotify,blockUI,schoolYear,printPost,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views = {};
			scope.views.title = 'Manage IDs';

			scope.views.currentPage = 1;			
			
			scope.generate = {};
			
			scope.enrollees = [];
			
			scope.filter = {};
			
			scope.filter_students = {};
			scope.filtered_students = [];

			// cached current school year
			scope.current_sy = {};
			
			// School Years			
			
			schoolYear.get(scope);			

			$http({
			  method: 'POST',
			  url: 'handlers/current-sy.php'
			}).then(function mySucces(response) {

				scope.filter.school_year = response.data;
				scope.current_sy = angular.copy(response.data);
				
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
			
			scope.btns = {
				edit: {
					disabled: true,
				}
			};
			
		};
		
		function validate(scope,form) {

			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {

				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder[form].$invalid;			
			
		};		
		
		self.filter = function(scope) {
			
			if (scope.filter.level === undefined) {
				pnotify.show('error','Notification','Please select level');				
				return;
			};
			
			list(scope);
			
		};
		
		function list(scope) {

			blockUI.show("Fetching students list please wait...");

			if (scope.$id > 2) scope = scope.$parent;

			scope.currentPage = scope.views.currentPage;
			scope.pageSize = 50;
			scope.maxSize = 5;

			scope.views.panel_title = 'Enrollees for School Year: '+scope.filter.school_year.school_year;

			$http({
			  method: 'POST',
			  url: 'handlers/enrollees-ids.php',
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
			$('#x_content').load('lists/enrollees-ids.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);
			});			
		
		};

		self.generateIds = function(scope) {
			
			if (scope.enrollees.length == 0) {
				pnotify.show('error','Notification','No students selected for IDs generation');
				return;
			};
			
			var onOk = function() {

				if (validate(scope,'ids')) return false;

				$http({
				  method: 'POST',
				  url: 'handlers/prepare-ids.php',
				  data: {level: scope.filter.level.id, method: scope.generate.method, enrollees: scope.enrollees}
				}).then(function mySucces(response) {
					
					generate(scope,0,scope.generate.method,response.data.start);
					// pnotify.show('success','Notification','Student successfully enrolled.');
					
				}, function myError(response) {
							  
					
				});
				
				return true;

			};
			
			bootstrapModal.box(scope,'Generate IDs','dialogs/generate-id.html',onOk,'Ok');
			
		};
		
		function generate(scope,i,method,cursor) {
			
			scope.enrollees[i].remarks = "Generating...";
			
			$http({
			  method: 'POST',
			  url: 'handlers/generate-ids.php',
			  data: {i: i, method: method, cursor: cursor, student: scope.enrollees[i]}
			}).then(function mySucces(response) {
				
				scope.enrollees[i].remarks = "Ok";								
				scope.enrollees[i].school_id = response.data.school_id;
				++i;
				
				$timeout(function() {
					if (i < scope.enrollees.length) {
						if (method == "all") {
							generate(scope,i,method,response.data.school_id);
						} else {
							generate(scope,i,method,response.data.cursor);
						};
					};
				}, 500);
				
			}, function myError(response) {
						  
				
			});			
			
		};
		
		self.print = function(scope,enrollment) {

			// if (!access.has(scope,scope.module.id,scope.module.privileges.print_enrollment)) return;
		
			if (scope.enrollees.length == 0) {
				pnotify.show('error','Notification','No students selected to print');				
				return;
			};			
		
			printPost.show('reports/enrollment.php',{filter:{id: enrollment.id}});
			
		};	

	};

	return new form();
	
});