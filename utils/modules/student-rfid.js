angular.module('student-rfid-module', ['ui.bootstrap','pnotify-module']).factory('form', function($http,$timeout,$compile,pnotify) {
	
	function form() {
		
		function isAO(val) {
			return val instanceof Array || val instanceof Object ? true : false;
		};	
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.student = {
				name: ''			
			};
			
			scope.student = {
				id: 0,
				name: '',
				school_id: '',				
				grade: '',
				section: '',
				rfid: ''
			};			
			
			scope.suggest_students = [];				
			
			scope.student_suggest = {};
			
			$timeout(function() {
				self.studentsSuggest(scope);
			},500);
			
		};	
		
		self.studentsSuggest = function(scope) {
			
			scope.views.student = {
				name: '',				
			};
			
			$http({
			  method: 'POST',
			  url: 'handlers/students-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_students);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		self.studentSelect = function(scope, item, model, label, event) {
			
			scope.views.student.name = item.fullname;
			scope.student_suggest = angular.copy(item);
			
		};
		
		self.load = function(scope) {
			
			scope.student = {
				id: scope.student_suggest.id,
				name: scope.student_suggest.fullname,
				school_id: scope.student_suggest.school_id,
				grade: scope.student_suggest.grade,
				section: scope.student_suggest.section,
				rfid: scope.student_suggest.rfid
			};
			
		};
		
		self.update = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/student-rfid-update.php',
			  data: scope.student
			}).then(function mySucces(response) {
				
				scope.student = {
					id: 0,
					name: '',
					school_id: '',
					grade: '',
					section: '',
					rfid: ''					
				};			
				
				pnotify.show('info','Notification','RFID updated for '+scope.student_suggest.fullname);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
	};
	
	return new form();
	
});