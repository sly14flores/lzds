var app = angular.module('payroll',['account-module','toggle-fullscreen','ui.bootstrap','payroll-module']);

app.controller('payrollCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'payroll',
		privileges: {
			generate_individual_payroll: 2,
			reproces_individual_payroll: 3,
			update_payroll_info: 4,
			print_individual_payroll: 5,
			print_payroll_sheet: 6,
			print_payroll_reports: 6,
		}
	};	
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Payroll';
	$scope.views.panel_title = 'Payroll';
	
	$scope.fullscreen = fullscreen;	

	$scope.form = form;
	
	$scope.formatThousandsWithRounding = function(n, dp){
	  var w = n.toFixed(dp), k = w|0, b = n < 0 ? 1 : 0,
		  u = Math.abs(w-k), d = (''+u.toFixed(dp)).substr(2, dp),
		  s = ''+k, i = s.length, r = '';
	  while ( (i-=3) > b ) { r = ',' + s.substr(i, 3) + r; }
	  return s.substr(0, i + 3) + r + (d ? '.'+d: '');
	};

	$scope.formatThousandsNoRounding = function(n, dp){
	  var e = '', s = e+n, l = s.length, b = n < 0 ? 1 : 0,
		  i = s.lastIndexOf('.'), j = i == -1 ? l : i,
		  r = e, d = s.substr(j+1, dp);
	  while ( (j-=3) > b ) { r = ',' + s.substr(j, 3) + r; }
	  return s.substr(0, j + 3) + r + 
		(dp ? '.' + d + ( d.length < dp ? 
			('00000').substr(0, dp - d.length):e):e);
	};	

});

app.filter('pagination', function() {
	  return function(input, currentPage, pageSize) {
	    if(angular.isArray(input)) {
	      var start = (currentPage-1)*pageSize;
	      var end = currentPage*pageSize;
	      return input.slice(start, end);
	    }
	  };
});