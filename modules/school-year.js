angular.module('school-year',[]).factory('schoolYear',function($http) {
	
	function schoolYear() {
		
		var self = this;
		
		self.get = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/school-years.php'
			}).then(function mySucces(response) {

				scope.school_years = response.data['school_years'];
				scope.categories = response.data['categories'];

			}, function myError(response) {

			  // error

			});
			
		}
		
	};
	
	return new schoolYear();
	
});