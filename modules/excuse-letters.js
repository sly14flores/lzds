angular.module('excuse-letters-module', ['ui.bootstrap','bootstrap-modal','school-year','pnotify-module','block-ui','window-open-post','module-access']).factory('letters', function($rootScope,$http,$timeout,$compile,bootstrapModal,schoolYear,pnotify,blockUI,printPost,access) {

	function letters() {
		
		var self = this;
		
		self.scope = {};

		self.data = function(scope) {
			
			self.scope = scope;
			
			$rootScope.views.letters = {};			
	
			$rootScope.data.letter = {};
			$rootScope.data.letter.id = 0;
			$rootScope.data.letter.dates = {};			
			$rootScope.data.letter.dates.data = [];
			$rootScope.data.letter.dates.dels = [];

			$rootScope.data.letters = [];
	
			$rootScope.pagination.letters = {};
			
			$rootScope.views.excuse_letter_panel_title = '';
			
			/*
			schoolYear.getSys().then((response) => {
				
				$rootScope.school_years = response.school_years;
				$rootScope.categories = response.categories;
				
			},(response) => {
				
			});		
			schoolYear.currentSy().then((response) => {
				
				$rootScope.current_sy = response.data;				
				
			}, (response) => {
				
			});
			*/
			
		};
		
		function validate() {
			
			var controls = $rootScope.formHolder.letter.$$controls;

			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) {
					self.scope.$apply(function() {
						elem.$touched = elem.$invalid;
					});
				}
									
			});

			return $rootScope.formHolder.letter.$invalid;
			
		};		
		
		self.list = function(opt) {
		
			$rootScope.views.letters.list = false;			
			
			$rootScope.data.letter = {};
			$rootScope.data.letter.id = 0;	
		
			$rootScope.pagination.letters.currentPage = 1;
			$rootScope.pagination.letters.pageSize = 15;
			$rootScope.pagination.letters.maxSize = 5;				

			$http({
			  method: 'POST',
			  url: 'handlers/excuse-letters-list.php',
			  data: {id: $rootScope.student_id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, $rootScope.data.letters);
				$rootScope.pagination.letters.filterData = $rootScope.data.letters;
				// $timeout(function() { scope.$apply(); }, 500);
				
			}, function myError(response) {
				 
			  // error
				
			});		
			
			if (opt) {
				$('#x_content_excuse_letters').html('Loading...');
				$('#x_content_excuse_letters').load('lists/excuse-letters.html',function() {				
					$compile($('#x_content_excuse_letters')[0])($rootScope);
				});
			};
			
		};
		
		self.letter = function(letter) {
		
			var title = 'Add Excuse Letter';

			if (letter == null) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.add_excuse_letter)) return;
				$rootScope.data.letter = {};
				$rootScope.data.letter.id = 0;				
				$rootScope.data.letter.student_id = $rootScope.student_id;
				$rootScope.data.letter.letter_sy = $rootScope.current_sy;
				$rootScope.data.letter.dates = {};			
				$rootScope.data.letter.dates.data = [];
				$rootScope.data.letter.dates.dels = [];				
			} else {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.view_excuse_letter)) return;
				$rootScope.data.letter = angular.copy(letter);
				angular.forEach($rootScope.data.letter.dates.data,function(item,i) {
					$rootScope.data.letter.dates.data[i].excuse_letter_date = new Date(item.excuse_letter_date);
				});
				title = 'Edit Excuse Letter Info';
			};

			var content = 'dialogs/excuse-letter.html';

			bootstrapModal.box($rootScope,title,content,self.save);			
			
		};

		self.dates = {
			
			add: function() {
				
				$rootScope.data.letter.dates.data.push({id:0, excuse_letter_date: new Date(), wholeday: "Wholeday", disabled: false});				
				
			},
			
			edit: function(date) {

				var index = $rootScope.data.letter.dates.data.indexOf(date);
				$rootScope.data.letter.dates.data[index].disabled = !$rootScope.data.letter.dates.data[index].disabled;
				
			},
			
			del: function(date) {							
				
				if (date.id > 0) {
					$rootScope.data.letter.dates.dels.push(date.id);
				}			

				var dates = $rootScope.data.letter.dates.data;
				var index = $rootScope.data.letter.dates.data.indexOf(date);
				$rootScope.data.letter.dates.data = [];		
				
				angular.forEach(dates, function(d,i) {
					
					if (index != i) {
						
						delete d['$$hashKey'];
						$rootScope.data.letter.dates.data.push(d);
						
					};
					
				});					
				
			}
			
		};
		
		self.save = function() {			
			
			if ($rootScope.data.letter.id > 0) {
				if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.update_excuse_letter)) return false;
			};
			
			if (validate()) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/excuse-letter-save.php',
			  data: $rootScope.data.letter
			}).then(function mySucces(response) {

				self.list(false);
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			return true;			
			
		};
		
		self.delete = function(row) {
			
			if (!access.has($rootScope,$rootScope.module.id,$rootScope.module.privileges.delete_excuse_letter)) return;
			
			var onOk = function() {		
				
				$http({
				  method: 'POST',
				  url: 'handlers/excuse-letter-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(false);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm($rootScope,'Confirmation','Are you sure you want to delete this excuse letter?',onOk,function() {});

		};
		
	};	
	
	return new letters();
	
});