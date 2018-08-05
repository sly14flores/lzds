var app = angular.module('staffs',['ui.bootstrap','account-module','toggle-fullscreen','staffs-module','leaves-module','travel-orders-module','loans-module','records-module']);

app.controller('staffsCtrl',function($scope,fullscreen,form,leaves,tos,loans,records) {
	
	$scope.module = {
		id: 'staffs',
		privileges: {
			add_staff: 2,
			view_staff: 3,
			edit_staff: 4,
			delete_staff: "delete_staff",
			add_leave: 5,
			view_leave: 6,
			update_leave: 7,
			delete_leave: "delete_leave",
			add_to: 8,
			view_to: 9,
			update_to: 10,
			delete_to: "delete_to",
			add_loan: 11,
			view_loan: 12,
			update_loan: 13,
			view_loan_payments: "view_loan_payments",
			delete_loan: "delete_loan",
			add_record: 14,
			view_record: 15,
			update_record: 16,
			print_record: 17,
			delete_record: "delete_record",	
		}
	};	
	
	$scope.views = {};	
	
	form.data($scope); // initialize data	
	
	$scope.views.title = 'Staffs';
	$scope.views.panel_title = 'Staffs List';
	
	$scope.fullscreen = fullscreen;
	
	form.list($scope);

	$scope.form = form;
	
	$scope.leaves = leaves;
	$scope.tos = tos;
	$scope.loans = loans;
	$scope.records = records;

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