angular.module('payroll-module', ['ui.bootstrap','bootstrap-modal','school-year','window-open-post','block-ui','pnotify-module','jspdf-module']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,printPost,blockUI,pnotify,jspdf) {
	
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
				employment_status: '',
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
					disabled: true,					
					label: 'Save'					
				},
				cancel: {
					disabled: false,					
					label: 'Cancel'		
				}
			};
			
			jspdf.init();
			
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
		
		function process(scope) {
			
			scope.sheet.individual.gross_pay = 0;
			scope.sheet.individual.total_deductions = 0;
			scope.sheet.individual.basic_pay = 0;			
			scope.sheet.individual.bonuses = 0;		
			
			angular.forEach(scope.sheet.individual.payroll_pays,function(item,i) {
				
				scope.sheet.individual.gross_pay += item.amount;
				
			});

			angular.forEach(scope.sheet.individual.payroll_deductions,function(item,i) {
				
				scope.sheet.individual.total_deductions += item.amount;
				
			});

			angular.forEach(scope.sheet.individual.payroll_bonuses,function(item,i) {
				
				scope.sheet.individual.bonuses += item.amount;
				
			});			
			
			$timeout(function() {
				
				scope.sheet.individual.net_pay = scope.sheet.individual.gross_pay - scope.sheet.individual.total_deductions + scope.sheet.individual.bonuses;
				
			},200);
			
		};
		
		self.individual = function(scope,reprocess) {

			if (scope.payroll.individual.id == 0) {
				pnotify.show('alert','Notification','No staff selected');				
				return;
			};
			
			if ((scope.payroll.individual.employment_status == 'EOC') || (scope.payroll.individual.employment_status == 'Resigned')) {
				pnotify.show('alert','Notification','Staff employment status is '+scope.payroll.individual.employment_status);
				return;
			};
			
			var onOk = function() {
			
				scope.sheet.individual = {};	
				scope.sheet.individual.id = 0;
				scope.sheet.individual.payroll_pays = [];
				scope.sheet.individual.payroll_deductions = [];
				scope.sheet.individual.payroll_bonuses = [];

				scope.views.panel_title = scope.payroll.individual.fullname+' ('+scope.periods[scope.payroll.individual.period]+', '+scope.payroll.individual.month.description+' '+scope.payroll.individual.year+')';				
				
				scope.payroll.individual.option = reprocess;
				
				$http({
				  method: 'POST',
				  url: 'handlers/payroll-individual.php',
				  data: scope.payroll.individual
				}).then(function mySucces(response) {					
					
					scope.sheet.individual = response.data;
					process(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});
				
				$('#x_content').html('Loading...');
				$('#x_content').load('lists/individual.html',function() {
					$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
				});

			};
		
			if (!reprocess) {
				
				onOk();
				
			} else {
				
				bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to re-process this payroll?',onOk,function() {});				
				
			};	
		
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;			
			
		};
		
		self.update = function(scope) {			
			
			$http({
			  method: 'POST',
			  url: 'handlers/payroll-individual-update.php',
			  data: scope.sheet.individual
			}).then(function mySucces(response) {
				
				self.individual(scope,false);
				scope.btns.ok.disabled = true;
				pnotify.show('success','Notification','Payroll Info Updated');
				
			}, function myError(response) {
				 
			  // error
				
			});					
			
		};
		
		self.staffSelect = function(scope, item, model, label, event) {

			scope.payroll.individual.fullname = item['fullname'];
			scope.payroll.individual.id = item['id'];
			scope.payroll.individual.employment_status = item['employment_status'];		

		};
		
		self.print = function(scope,payroll) {
			
			var doc = new jsPDF({
				orientation: 'portrait',
				unit: 'pt',
				format: [792, 612]
			});

			doc.setFontSize(10);
			doc.setTextColor(40,40,40);
			doc.text(103, 20, 'Municipality of San Juan');
			
			var blob = doc.output("blob");
			window.open(URL.createObjectURL(blob));			
			
		};
		
	};
	
	return new form();
	
});