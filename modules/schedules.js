angular.module('schedules-module', ['bootstrap-modal','school-year','ui.bootstrap','block-ui','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,blockUI,access) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};
			
			scope.views.list = false;
			
			scope.schedule = {};
			scope.schedule.id = 0;
			
			scope.schedule_details = [
				{
				  id: 0,
				  schedule_day: "Monday",
				  duration: "Wholeday",				  
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Tuesday",
				  duration: "Wholeday",				  
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Wednesday",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Thursday",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Friday",
				  duration: "Wholeday",				  
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Saturday",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Sunday",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
			];			
			
			scope.schedules = [];
			
			scope.sections = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/sections.php'
			}).then(function mySucces(response) {
				
				scope.sections = angular.copy(response.data);
				
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
			
			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder[form].$invalid;
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.panel_title = 'Edit Schedule Info';			
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Schedule';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.schedule = function(scope,row) { // form						
			
			if (row == null) {
				if (!access.has(scope,scope.module.id,scope.module.privileges.add_schedule)) return;
			} else {
				if (!access.has(scope,scope.module.id,scope.module.privileges.view_schedule)) return;				
			};
			
			if (scope.$id > 2) scope = scope.$parent;			
			
			blockUI.show();
			
			scope.views.list = true;			
			
			mode(scope,row);		
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/schedule.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },500);				
			});

			if (row != null) {
			
				$http({
				  method: 'POST',
				  url: 'handlers/schedule-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {				
					
					angular.copy(response.data['schedule'], scope.schedule);
					
					if (response.data['schedule_details'].length == 0) {
						response.data['schedule_details'] = angular.copy(scope.schedule_details);
					};
					
					angular.forEach(response.data['schedule_details'],function(item,i) {
						response.data['schedule_details'][i].morning_in = new Date("2000-01-01 "+item.morning_in);
						response.data['schedule_details'][i].morning_cutoff = new Date("2000-01-01 "+item.morning_cutoff);
						response.data['schedule_details'][i].morning_out = new Date("2000-01-01 "+item.morning_out);
						response.data['schedule_details'][i].lunch_break_cutoff = new Date("2000-01-01 "+item.lunch_break_cutoff);
						response.data['schedule_details'][i].afternoon_in = new Date("2000-01-01 "+item.afternoon_in);
						response.data['schedule_details'][i].afternoon_cutoff = new Date("2000-01-01 "+item.afternoon_cutoff);
						response.data['schedule_details'][i].afternoon_out = new Date("2000-01-01 "+item.afternoon_out);
					});
					
					angular.copy(response.data['schedule_details'], scope.schedule_details);
					
					blockUI.hide();
					
				}, function myError(response) {
					 
					blockUI.hide();
					
				});
				
			} else {
				
				blockUI.hide();
				
			};
			
		};
		
		self.edit = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.edit_schedule)) return;
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.list = function(scope) {			
			
			if (scope.$id > 2) scope = scope.$parent;				
			
			blockUI.show();
			
			scope.views.list = false;		
		
			scope.schedule = {};
			scope.schedule.id = 0;		
		
			scope.schedule_details = [
				{
				  id: 0,
				  schedule_day: "Mon",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Tue",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Wed",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Thu",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Fri",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Sat",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},
				{
				  id: 0,
				  schedule_day: "Sun",
				  duration: "Wholeday",
				  morning_in: new Date("0"),
				  morning_cutoff: new Date("0"),
				  morning_out: new Date("0"),
				  lunch_break_cutoff: new Date("0"),
				  afternoon_in: new Date("0"),
				  afternoon_cutoff: new Date("0"),
				  afternoon_out: new Date("0"),
				  disabled: false
				},	
			];
		
			scope.currentPage = 1;
			scope.pageSize = 25;
			scope.maxSize = 5;			
		
			scope.views.panel_title = 'Schedules List';		

			$http({
			  method: 'POST',
			  url: 'handlers/schedules-list.php'
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.schedules);
				scope.filterData = scope.schedules;
				blockUI.hide();
				
			}, function myError(response) {
				 
				blockUI.hide();
				
			});	
			
			$('#x_content').html('Loading...');
			$('#x_content').load('lists/schedules.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});
			
		};

		self.save = function(scope,form) {		
			
			if (validate(scope,form)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/schedule-save.php',
			  data: {schedule: scope.schedule, schedule_details: scope.schedule_details}
			}).then(function mySucces(response) {

				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});		
		
		};
		
		self.delete = function(scope,row) {
			
			if (scope.$id > 2) scope = scope.$parent;			
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.delete_schedule)) return;
			
			var onOk = function() {		
				
				$http({
				  method: 'POST',
				  url: 'handlers/schedule-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});

		};
		
		self.schedule_item = function(scope,row) {

			row.disabled = !row.disabled;
			
		};

		self.schedule_clone = function(scope,row) {

			var index = scope.schedule_details.indexOf(row);
			
			scope.schedule_details[index]['duration'] = scope.schedule_details[0]['duration'];
			scope.schedule_details[index]['morning_in'] = scope.schedule_details[0]['morning_in'];
			scope.schedule_details[index]['morning_cutoff'] = scope.schedule_details[0]['morning_cutoff'];
			scope.schedule_details[index]['morning_out'] = scope.schedule_details[0]['morning_out'];
			scope.schedule_details[index]['lunch_break_cutoff'] = scope.schedule_details[0]['lunch_break_cutoff'];
			scope.schedule_details[index]['afternoon_in'] = scope.schedule_details[0]['afternoon_in'];
			scope.schedule_details[index]['afternoon_cutoff'] = scope.schedule_details[0]['afternoon_cutoff'];
			scope.schedule_details[index]['afternoon_out'] = scope.schedule_details[0]['afternoon_out'];
			
		};		
		
	};
	
	return new form();
	
});