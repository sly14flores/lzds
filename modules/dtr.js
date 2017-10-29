angular.module('dtr-module', ['ui.bootstrap','bootstrap-modal']).factory('form', function($http,$timeout,$compile,bootstrapModal) {
	
	function form() {
		
		var self = this;
		
		self.data = function(scope) { // initialize data
			
			scope.formHolder = {};		

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
			
			var d = new Date();

			scope.staffDtr = {
				id: 0,
				rfid: '',
				fullname: '',
				month: scope.months[d.getMonth()],
				year: d.getFullYear(),
				option: false
			};
			
			scope.downloadDtr = {
				year: d.getFullYear(),
				month: scope.months[d.getMonth()]
			};
			
			scope.logs = [];
			
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
			
			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder[form].$invalid;
			
		};
		
		function mode(scope,row) {
			
			if (row != null) {
				scope.views.panel_title = 'Edit Staff Info';			
				scope.btns.ok.disabled = true;
				scope.btns.ok.label = 'Update';
				scope.btns.cancel.label = 'Close';			
			} else {
				scope.views.panel_title = 'Add Staff';
				scope.btns.ok.disabled = false;	
				scope.btns.ok.label = 'Save';
				scope.btns.cancel.label = 'Cancel';
			}
			
		};		
		
		self.staff = function(scope,row) { // form
			
			mode(scope,row);			
			
			$('#x_content').html('Loading...');
			$('#x_content').load('forms/staff.html',function() {
				$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
			});
						
			if (row != null) {
				if (scope.$id > 2) scope = scope.$parent;
				
				$http({
				  method: 'POST',
				  url: 'handlers/staff-edit.php',
				  data: {id: row.id}
				}).then(function mySucces(response) {
					
					angular.copy(response.data, scope.staff);
					if (scope.staff.birthday != null) scope.staff.birthday = new Date(scope.staff.birthday);
					
				}, function myError(response) {
					 
				  // error
					
				});					
			};			
			
		};
		
		self.edit = function(scope) {
			
			scope.btns.ok.disabled = !scope.btns.ok.disabled;
			
		};		
		
		self.dtr = function(scope,opt) {			
			
			if (scope.staffDtr.id == 0) return;							
			
			var onOk = function() {
			
				scope.staffDtr.option = opt;
			
				scope.views.panel_title = scope.staffDtr.fullname+' ('+scope.staffDtr.month.description+' '+scope.staffDtr.year+')';
			
				scope.dtr = [];			
			
				$http({
				  method: 'POST',
				  url: 'handlers/dtr-staff.php',
				  data: scope.staffDtr
				}).then(function mySucces(response) {					
					
					scope.dtr = angular.copy(response.data);
					
				}, function myError(response) {
					 
				  // error
					
				});
				
				$('#x_content').html('Loading...');
				$('#x_content').load('lists/dtr.html',function() {
					$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
				});				

			};
			
			if (!opt) {
				
				onOk();
				
			} else {
				
				bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to re-analyze dtr?',onOk,function() {});				
				
			}			

		};

		self.save = function(scope) {			
			
			if (validate(scope)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/staff-save.php',
			  data: scope.staff
			}).then(function mySucces(response) {
				
				self.list(scope);
				
			}, function myError(response) {
				 
			  // error
				
			});		
		
		};
		
		self.delete = function(scope,row) {
			
			var onOk = function() {
				
				if (scope.$id > 2) scope = scope.$parent;			
				
				$http({
				  method: 'POST',
				  url: 'handlers/staff-delete.php',
				  data: {id: [row.id]}
				}).then(function mySucces(response) {

					self.list(scope);
					
				}, function myError(response) {
					 
				  // error
					
				});

			};

			bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to delete this record?',onOk,function() {});						

		};
		
		self.download = function(scope) {
			
			if (validate(scope,'downloadDtr')) return;

			scope.downloadProgress = 0;
			scope.downloadProgressStatus = 'Downloading logs please wait...';
			scope.downloadI = 0;
			scope.logs = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/logs-fetch.php',
			  data: scope.downloadDtr
			}).then(function mySucces(response) {
				
				scope.downloadProgressStatus = 'Initiating import...';
				scope.logs = angular.copy(response.data);
				if (scope.logs.length > 0) {
					download(scope,scope.downloadI);					
				}
				
			}, function myError(response) {
				 
				scope.downloadProgressStatus = 'Cannot connect to Kiosk please check connectivity';
				
			});			
			
		};
		
		function download(scope,i) {
			
			$http({
				method: 'POST',
				url: 'handlers/dtr-import.php',
				data: scope.logs[i]
			}).then(function mySucces(response) {
				
				++scope.downloadI;
				scope.downloadProgress = Math.ceil(scope.downloadI*100/(scope.logs.length));
				scope.downloadProgressStatus = 'Imported '+scope.downloadI+' of '+scope.logs.length+' logs ('+scope.downloadProgress+'%)';
				if (scope.downloadI < scope.logs.length) {
					download(scope,scope.downloadI);
				} else {
				scope.downloadProgressStatus = 'Imported '+scope.downloadI+' of '+scope.logs.length+' logs ('+scope.downloadProgress+'%). Import Complete';
				}
				
			}, function myError(response) {
				
				
			});			
			
		}		
		
		self.staffSelect = function(scope, item, model, label, event) {

			scope.staffDtr.fullname = item['fullname'];
			scope.staffDtr.id = item['id'];
			scope.staffDtr.rfid = item['rfid'];

		};
		
		self.logs = function(scope,row) {			
			
			scope.backlogs = [];			
			
			scope.dtr_day = angular.copy(row);			
			scope.dtr_day.morning_in = new Date(row.ddate+" "+row.morning_in);
			scope.dtr_day.morning_out = new Date(row.ddate+" "+row.morning_out);
			scope.dtr_day.afternoon_in = new Date(row.ddate+" "+row.afternoon_in);
			scope.dtr_day.afternoon_out = new Date(row.ddate+" "+row.afternoon_out);						
			
			scope.dtr_day.disabled = {};
			scope.dtr_day.disabled.morning_in = row.morning_in == '-'?false:true;
			scope.dtr_day.disabled.morning_out = row.morning_out == '-'?false:true;
			scope.dtr_day.disabled.afternoon_in = row.afternoon_in == '-'?false:true;
			scope.dtr_day.disabled.afternoon_out = row.afternoon_out == '-'?false:true;
			
			$http({
			  method: 'POST',
			  url: 'handlers/backlogs.php',
			  data: {rfid: row.rfid, date: row.ddate}
			}).then(function mySucces(response) {
				
				scope.backlogs = angular.copy(response.data);
				
			}, function myError(response) {				 
				
			});				
			
			var content = 'dialogs/log.html';			

			bootstrapModal.box(scope,row.day+' - '+row.date,content,self.dtrLogs);						
			
		};
		
		self.allot = function(scope,bl) {
			
			if (scope.dtr_allotment == "") return;
			
			scope.dtr_day[scope.dtr_allotment] = new Date("2000-01-01 "+bl.log);
			
		};
		
		self.dtrLogs = function(scope) {
			
			$http({
			  method: 'POST',
			  url: 'handlers/dtr-day-logs.php',
			  data: scope.dtr_day
			}).then(function mySucces(response) {
				
				self.dtr(scope,false);
				
			}, function myError(response) {				 
				
			});		
			
		};
		
	};
	
	return new form();
	
});