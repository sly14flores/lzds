angular.module('dtr-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','block-ui','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,blockUI,access) {
	
	function form() {
		
		function isAO(val) {
			return val instanceof Array || val instanceof Object ? true : false;
		};
		
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
				option: false,
				schedule_id: 0
			};
			
			scope.downloadDtr = {
				by: "Month",
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
		
		self.dtr = function(scope,opt) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.view_dtr)) return;			
			
			if (opt) if (!access.has(scope,scope.module.id,scope.module.privileges.re_analyze_dtr)) return;
			
			if (scope.staffDtr.id == 0) {
				pnotify.show('error','Notification','Please select staff');
				return;
			};

			if (scope.staffDtr.schedule_id == 0) {
				pnotify.show('error','Notification','Staff has no defined schedule, please set it first in staff info under DTR info');
				return;
			};
			
			var onOk = function() {
				
				blockUI.show('Analyzing dtr please wait...');
				
				scope.staffDtr.option = opt;
			
				scope.views.panel_title = scope.staffDtr.fullname+' ('+scope.staffDtr.month.description+' '+scope.staffDtr.year+')';
			
				scope.dtr = [];			
			
				$http({
				  method: 'POST',
				  url: 'handlers/dtr-staff.php',
				  data: scope.staffDtr
				}).then(function mySucces(response) {					
					
					scope.dtr = angular.copy(response.data);
					blockUI.hide();
					
				}, function myError(response) {
					 
					blockUI.hide();
					
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
		
		self.download = function(scope) {

			if (!access.has(scope,scope.module.id,scope.module.privileges.import_dtr)) return;		
		
			if (validate(scope,'downloadDtr')) return;

			scope.downloadProgress = 0;
			scope.downloadProgressStatus = 'Downloading logs please wait...';
			scope.downloadI = 0;
			scope.logs = [];
			
			$http({
			  method: 'POST',
			  // url: 'handlers/logs-fetch.php',
			  url: 'http://192.168.1.30/logs-fetch.php',
			  data: scope.downloadDtr
			}).then(function mySucces(response) {
				
				if (isAO(response.data)) {
				
					scope.logs = angular.copy(response.data);
					if (scope.logs.length > 0) {
						scope.downloadProgressStatus = 'Initiating import...';
						download(scope,scope.downloadI);					
					} else {
						scope.downloadProgressStatus = 'No logs found';						
					}
				
				};
				
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
			scope.staffDtr.schedule_id = item['schedule_id'];

		};
		
		self.staffSelected = function(scope) {
			
			var staff = scope.staffDtr.staff;			
			
			scope.staffDtr.fullname = staff['fullname'];
			scope.staffDtr.id = staff['id'];
			scope.staffDtr.rfid = staff['rfid'];
			scope.staffDtr.schedule_id = staff['schedule_id'];			
			
		};
		
		self.logs = function(scope,row) {			
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.view_logs)) return;			
			
			scope.backlogs = [];			
			
			scope.dtr_day = angular.copy(row);			
			scope.dtr_day.morning_in = new Date(row.ddate+" "+row.morning_in);
			scope.dtr_day.morning_out = new Date(row.ddate+" "+row.morning_out);
			scope.dtr_day.afternoon_in = new Date(row.ddate+" "+row.afternoon_in);
			scope.dtr_day.afternoon_out = new Date(row.ddate+" "+row.afternoon_out);						
			
			scope.dtr_day.disabled = {};
			scope.dtr_day.manual = {};
			
			$http({
			  method: 'POST',
			  url: 'handlers/backlogs.php',
			  data: {dtr: 'dtr', manual: 'staffs_manual_logs', id: scope.staffDtr.id, rfid: row.rfid, date: row.ddate, dtr_id: row.id}
			}).then(function mySucces(response) {

				scope.backlogs = angular.copy(response.data.backlogs);
				scope.dtr_day.disabled = angular.copy(response.data.disabled);
				scope.dtr_day.manual = angular.copy(response.data.manual);

			}, function myError(response) {

			});
			
			var content = 'dialogs/log.html';			

			bootstrapModal.box2(scope,row.day+' - '+row.date,content,self.dtrLogs,'Save');						
			
		};
		
		self.manualLogCheck = function(scope,log) {
			
			scope.dtr_day.disabled[log] = !scope.dtr_day.disabled[log];
			
			if (scope.dtr_day.disabled[log]) {

				scope.dtr_day.manual[log].save = true;
				
			};
			
		};		
		
		self.allot = function(scope,bl) {
			
			if (scope.dtr_allotment == "") return;
			
			scope.dtr_day[scope.dtr_allotment] = new Date("2000-01-01 "+bl.log);
			scope.dtr_day.manual[scope.dtr_allotment].save = false;			
			
		};
		
		self.dtrLogs = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.manage_logs)) return false;
			
			$http({
			  method: 'POST',
			  url: 'handlers/dtr-day-logs.php',
			  data: {dtr: "dtr", manual: "staffs_manual_logs", staff_id: scope.staffDtr.id, day: scope.dtr_day}
			}).then(function mySucces(response) {
				
				self.dtr(scope,false);
				
			}, function myError(response) {				 
				
			});		
			
		};
		
	};
	
	return new form();
	
});