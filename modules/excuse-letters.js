angular.module('excuse-letters-module', ['ui.bootstrap','bootstrap-modal','school-year','pnotify-module','block-ui','window-open-post']).factory('letters', function($http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI,printPost) {
	
	function letters() {
		
		var self = this;
		
		self.data = function(scope) {
			
			scope.views.letters = {};			
	
			scope.data.letter = {};
			scope.data.letter.id = 0;
			scope.data.letters = [];
	
			scope.pagination.letters = {};
			
			scope.views.excuse_letter_panel_title = '';
			
			schoolYear.get(scope);			
			schoolYear.current(scope);			
			
		};
		
		function validate(scope) {
			
			var controls = scope.formHolder.letter.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder.letter.$invalid;
			
		};		
		
		self.list = function(scope,opt) {
		
			scope.views.letters.list = false;			
			
			scope.data.letter = {};
			scope.data.letter.id = 0;	
		
			scope.pagination.letters.currentPage = 1;
			scope.pagination.letters.pageSize = 15;
			scope.pagination.letters.maxSize = 5;				

			$http({
			  method: 'POST',
			  url: 'handlers/excuse-letters-list.php',
			  data: {id: scope.student_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.data.letters);
				scope.pagination.letters.filterData = scope.data.letters;
				$timeout(function() { scope.$apply(); }, 500);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			if (opt) {
				$('#x_content_excuse_letters').html('Loading...');
				$('#x_content_excuse_letters').load('lists/excuse-letters.html',function() {				
					$compile($('#x_content_excuse_letters')[0])(scope);
				});
			};
			
		};
		
		self.letter = function(scope,letter) {

			var title = 'Add Excuse Letter';

			if (letter == null) {
				scope.data.letter = {};
				scope.data.letter.id = 0;
				scope.data.letter.student_id = scope.student_id;
				scope.data.letter.record_sy = scope.current_sy;
			} else {
				scope.data.letter = angular.copy(letter);
				title = 'Edit Excuse Letter Info';
			};

			var content = 'dialogs/excuse-letter.html';

			bootstrapModal.box(scope,title,content,self.save);			
			
		};

		self.save = function(scope) {			
			
			if (validate(scope)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/excuse-letter-save.php',
			  data: scope.data.letter
			}).then(function mySucces(response) {

				self.list(scope,false);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {		
				
				$http({
				  method: 'POST',
				  url: 'handlers/excuse-letter-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope,false);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this excuse letter?',onOk,function() {});

		};
		
	};	
	
	return new letters();
	
});