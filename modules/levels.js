angular.module('levels-module', ['ui.bootstrap','bootstrap-modal','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) {				
	
			scope.level = {};
			scope.level.id = 0;
			scope.level.sections = [];
			scope.level.dels = [];

			scope.levels = [];
			
			scope.teachers = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/teachers.php'
			}).then(function mySucces(response) {
				
				scope.teachers = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.level.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.level.$invalid;
			
		};	
		
		self.list = function(scope) {

			if (scope.$id > 2) scope = scope.$parent;		
		
			scope.views.list = false;		
			
			scope.level = {};
			scope.level.id = 0;
		
			scope.currentPage = 1;
			scope.pageSize = 15;
			scope.maxSize = 5;
		
			scope.views.panel_title = 'Levels';		

			$http({
			  method: 'POST',
			  url: 'handlers/levels-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.levels);
				scope.filterData = scope.levels;			
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/levels.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});				
			
		};
		
		self.level = function(scope,level) {
		
			if (level == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_level)) return;
				scope.level = {};
				scope.level.id = 0;
				scope.level.sections = [];
				scope.level.dels = [];				
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_level)) return;
				scope.level = angular.copy(level);
				scope.level.dels = [];
			};
			
			var content = 'dialogs/level.html';	

			bootstrapModal.box(scope,'Add Level',content,self.save);			

		};

		self.save = function(scope) {					

			if (scope.level.id > 0) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.update_level)) return false;
			};
		
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/level-save.php',
			  data: scope.level
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_level)) return false;
			
			var onOk = function() {					
				
				$http({
				  method: 'POST',
				  url: 'handlers/level-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this level?',onOk,function() {});

		};
		
		self.addSection = function(scope) {
			
			scope.level.sections.push({id:0, description: '', teacher: {id:0, fullname: ''}, disabled: false});
			
		};
		
		self.editSection = function(scope,section) {

			var index = scope.level.sections.indexOf(section);
			scope.level.sections[index].disabled = !scope.level.sections[index].disabled;
			
		};
		
		self.removeSection = function(scope,section) {
			
			var index = scope.level.sections.indexOf(section);
			scope.level.sections.splice(index,1);
			
			scope.level.dels.push(section.id);
			
		};
		
	};	
	
	return new form();
	
});