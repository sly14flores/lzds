angular.module('payroll-module', ['ui.bootstrap','bootstrap-modal','school-year','window-open-post','block-ui']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,printPost,blockUI) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};		
			
			scope.sheet = {};
			
			scope.sheet.individual = {};
			scope.sheet.individual.id = 0;
			scope.sheet.individual.payroll_pays = [];
			scope.sheet.individual.payroll_deductions = [];
			scope.sheet.individual.payroll_bonuses = [];
			
			scope.sheet.all = [];
			
			scope.months = [
				{month:"01",description:"January"},
				{month:"02",description:"February"},
				{month:"03",description:"March"},
				{month:"04",description:"April"},
				{month:"05",description:"May"},
				{month:"06",description:"June"},
				{month:"07",description:"July"},
				{month:"08",description:"August"},
				{month:"09",description:"September"},
				{month:"10",description:"October"},
				{month:"11",description:"November"},
				{month:"12",description:"December"},
			];
			
			scope.periods = {first: "First Half", second: "Second Half"};
			
			var d = new Date();
			
			scope.payroll = {};
			scope.payroll.all = {
				month: scope.months[d.getMonth()],
				period: "first",
				year: d.getFullYear(),
				option: false			
			};
			scope.payroll.individual = {
				id: 0,
				fullname: '',
				month: scope.months[d.getMonth()],
				period: "first",
				year: d.getFullYear(),
				option: false				
			};		
			
			scope.suggest_staffs = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/staffs-suggest.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_staffs);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			scope.btns = {
				ok: {
					disabled: false,					
					label: 'Save'					
				},
				cancel: {
					disabled: false,					
					label: 'Cancel'		
				}
			};			
			
		};		
		
		function validate(scope,form) {
			
			var controls = scope.formHolder['form'].$$controls;

			angular.forEach(controls,function(elem,i) {

				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder['form'].$invalid;
			
		};
		
		self.all = function(scope,reprocess) {
			
		};

		self.individual = function(scope,reprocess) {

			if (scope.payroll.individual.id == 0) return;			

			scope.sheet.individual = {};		
			scope.sheet.individual.id = 0;
			scope.sheet.individual.payroll_pays = [];
			scope.sheet.individual.payroll_deductions = [];
			scope.sheet.individual.payroll_bonuses = [];

			scope.views.panel_title = scope.payroll.individual.fullname+' ('+scope.periods[scope.payroll.individual.period]+', '+scope.payroll.individual.month.description+' '+scope.payroll.individual.year+')';				
		
			$http({
			  method: 'POST',
			  url: 'handlers/payroll-individual.php',
			  data: scope.payroll.individual
			}).then(function mySucces(response) {									
				
				
				
			}, function myError(response) {
				 
			  // error
				
			});
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/individual.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});		
		
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;			
			
		};
		
		self.staffSelect = function(scope, item, model, label, event) {

			scope.payroll.individual.fullname = item['fullname'];
			scope.payroll.individual.id = item['id'];

		};
		
	};
	
	return new form();
	
});