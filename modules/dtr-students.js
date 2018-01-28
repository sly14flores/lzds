angular.module('dtr-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','school-year']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,schoolYear) {
	
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
			
			$http({
			  method: 'POST',
			  url: 'handlers/grade-levels.php'
			}).then(function mySucces(response) {
				
				scope.levels = response.data;
				
			}, function myError(response) {
				 
			  // error
				
			});				

			scope.sections = [];
			
			schoolYear.get(scope);

			var d = new Date();

			scope.studentDtr = {
				by: "Student",
				id: 0,
				rfid: '',
				fullname: '',
				sgrade: '',
				ssection: '',
				grade: {id:0,description:""},
				section: {id:0,description:""},
				sy: {id:0,school_year:""},
				month: scope.months[d.getMonth()],
				year: d.getFullYear(),
				option: false
			};			
			
			$timeout(function() {
				$http({
				  method: 'POST',
				  url: 'handlers/current-sy.php'
				}).then(function mySucces(response) {

					scope.studentDtr.sy = response.data;
					$timeout(function() { self.studentsSuggest(scope); },500);
					
				}, function myError(response) {
					 
				  // error
					
				});
			},500);

			scope.downloadDtr = {
				by: "Month",				
				year: d.getFullYear(),
				month: scope.months[d.getMonth()]
			};

			scope.logs = [];
			
			scope.suggest_students = [];				
			
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
			
			scope.dtr = {};
			scope.dtr.student = [];
			
		};
		
		function validate(scope,form) {
			
			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder[form].$invalid;
			
		};		
		
		self.studentsSuggest = function(scope) {
			
			scope.studentDtr.fullname = '';
			
			$http({
			  method: 'POST',
			  url: 'handlers/students-suggest-dtr.php',
			  data: {sy: scope.studentDtr.sy.id}
			}).then(function mySucces(response) {
				
				angular.copy(response.data, scope.suggest_students);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		self.dtr = function(scope,opt) {				
			
			switch (scope.studentDtr.by) {
				
				case 'Student':
				
					if (scope.studentDtr.id == 0) {
						pnotify.show('danger','Notification','Please select student');
						return;
					};

					if (scope.studentDtr.ssection == '') {
						pnotify.show('danger','Notification','Student has no defined section, please set it first in the student current enrollment');
						return;
					};				
				
					var onOk = function() {
					
						scope.studentDtr.option = opt;
					
						scope.views.panel_title = scope.studentDtr.fullname+': '+scope.studentDtr.sgrade+' '+scope.studentDtr.ssection+' ('+scope.studentDtr.month.description+' '+scope.studentDtr.year+')';
					
						scope.dtr.student = [];			
					
						$http({
						  method: 'POST',
						  url: 'handlers/dtr-student.php',
						  data: scope.studentDtr
						}).then(function mySucces(response) {					
							
							scope.dtr.student = angular.copy(response.data);
							
						}, function myError(response) {
							 
						  // error
							
						});
						
						$('#x_content').html('Loading...');
						$('#x_content').load('lists/dtr-student.html',function() {
							$timeout(function() { $compile($('#x_content')[0])(scope); },100);				
						});				

					};
					
					if (!opt) {
						
						onOk();
						
					} else {
						
						bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to re-analyze dtr?',onOk,function() {});				
						
					}				
				
				break;
				
				case 'Section':
						
					if (scope.studentDtr.grade.id == 0) {
						pnotify.show('danger','Notification','Please select grade');
						return;
					};

					if (scope.studentDtr.section.id == 0) {
						pnotify.show('danger','Notification','Please select section');
						return;
					};

					var onOk = function() {
					
						scope.studentDtr.option = opt;
					
						scope.views.panel_title = scope.studentDtr.grade.description+'/'+scope.studentDtr.section.description+' ('+scope.studentDtr.month.description+' '+scope.studentDtr.year+')';					
						
						$('#x_content').html('Loading...');
						$('#x_content').load('lists/dtr-sections.html',function() {
							$timeout(function() {
								$compile($('#x_content')[0])(scope);
								startSectionDtr(scope);
							},100);
						});			

					};

					if (!opt) {
						
						onOk();
						
					} else {
						
						bootstrapModal.confirm(scope,'Confirmation','Are you sure you want to re-analyze dtr?',onOk,function() {});				
						
					}

					function startSectionDtr(scope) {

						$timeout(function() {
							$('#dtr-sections').append('<h5>Fetching students for '+scope.studentDtr.grade.description+' '+scope.studentDtr.section.description+'...</h5>');
							$http({
							  method: 'POST',
							  url: 'handlers/dtr-sections.php',
							  data: scope.studentDtr
							}).then(function mySucces(response) {											
								
								var msg = '';
								if (response.data.length > 0) {
									msg = '<h5>'+response.data.length+' students found...</h5>';
									$('#dtr-sections').append(msg);
									$timeout(function() {
										msg = '<h5>Initiating DTR analyzer...</h5>';
										$('#dtr-sections').append(msg);
										initAnalyze(scope,response.data);
									},100);
								} else {
									msg = '<h5>No students records found...</h5>';
									$('#dtr-sections').append(msg);
								}							
								
							}, function myError(response) {
								 
							  // error
								
							});							
						},300);
						
						/*
						** start analyzing dtr
						*/
						function initAnalyze(scope,students) {
						
							scope.analyze = {};
							scope.analyze.i = 0;
						
							function analyzeDtr(scope,students) {
								
								scope.analyze.id = students[scope.analyze.i]['id'];
								scope.analyze.rfid = students[scope.analyze.i]['rfid'];
								scope.analyze.year = scope.studentDtr.year;
								scope.analyze.month = scope.studentDtr.month;
								scope.analyze.option = scope.studentDtr.option;								
								
								$('#dtr-sections').append('<span>Analyzing DTR for <strong>'+students[scope.analyze.i]['fullname']+'</strong> ('+(scope.analyze.i+1).toString()+'/'+students.length.toString()+')</span>');
								
								$http({
								  method: 'POST',
								  url: 'handlers/dtr-student.php',
								  data: scope.analyze
								}).then(function mySucces(response) {					
									
									++scope.analyze.i;
									$('#dtr-sections').append('<span>...done</span><br>');
									$timeout(function() {
										if (scope.analyze.i < students.length) {
											analyzeDtr(scope,students);
										} else {
											generateExcel(scope);
										}
									},200);
									
								}, function myError(response) {
									 
								  // error
									
								});							
								
							};
							
							analyzeDtr(scope,students);

						};
						
						/*
						** generate excel
						*/
						function generateExcel(scope) {
							$('#dtr-sections').append('<h5>Generating excel...</h5>');
						};
						
					};
				
				break;
				
			};

		};
		
		self.download = function(scope) {
			
			if (validate(scope,'downloadDtr')) return;

			scope.downloadProgress = 0;
			scope.downloadProgressStatus = 'Downloading logs please wait...';
			scope.downloadI = 0;
			scope.logs = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/logs-fetch-students.php',
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

		self.studentSelect = function(scope, item, model, label, event) {

			scope.studentDtr.fullname = item['fullname'];
			scope.studentDtr.sgrade = item['grade'];
			scope.studentDtr.ssection = item['section'];
			scope.studentDtr.id = item['id'];
			scope.studentDtr.rfid = item['rfid'];

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