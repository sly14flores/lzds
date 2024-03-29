var app = angular.module('cashier',['account-module','toggle-fullscreen','cashier-module','ui.bootstrap']);

app.controller('cashierCtrl',function($scope,fullscreen,form) {
	
	$scope.module = {
		id: 'cashier',
		privileges: {
			enrollment_payments: 2,
			add_payment: 3,
			update_payment: 4,
			generate_soa: 5,
			print_payment: 6,
			send_email: 7,
			send_bulk_emails: 8,
			delete_payment: "delete_payment",
		}
	};	
	
	$scope.views = {};	
	
	$scope.filter = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Cashier';
	$scope.views.panel_title = 'Payments';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

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