angular.module('dtr-module', ['ui.bootstrap','bootstrap-modal','pnotify-module','school-year','block-ui','module-access','jspdf-module']).factory('form', function($http,$timeout,$compile,bootstrapModal,pnotify,schoolYear,blockUI,access,jspdf) {
	
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
				by: "Individual_Student",
				id: 0,
				rfid: '',
				fullname: '',
				select_student: '',
				sgrade: '',
				ssection: '',
				grade: {id:0,description:""},
				section: {id:0,description:""},
				sy: {id:0,school_year:""},
				month: scope.months[d.getMonth()],
				year: d.getFullYear(),
				option: false
			};			
			
			scope.studentDtr.by = "Section";
			
			scope.select_students = [];
			
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
			
			jspdf.init();			
			
		};
		
		function validate(scope,form) {
			
			var controls = scope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {
				
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder[form].$invalid;
			
		};		
		
		self.byChange = function(scope) {
			
			var d = new Date();
			
			scope.studentDtr.id = 0;
			scope.studentDtr.rfid = '';
			scope.studentDtr.fullname = ''
			scope.studentDtr.select_student = ''
			scope.studentDtr.sgrade = '';
			scope.studentDtr.ssection = '';
			scope.studentDtr.grade = {id:0,description:""};
			scope.studentDtr.section = {id:0,description:""};
			// scope.studentDtr.sy = {id:0,school_year:""};
			// scope.studentDtr.month = scope.months[d.getMonth()];
			// scope.studentDtr.year = d.getFullYear();
			scope.studentDtr.option = false;

			switch (scope.studentDtr.by) {
				
				case "Section":								

					studentsSelect(scope);
				
				break;				
				
			};
			
		};
		
		function studentsSelect(scope) {

			if (scope.studentDtr.grade !== undefined) scope.sections = scope.studentDtr.grade.sections;
			
			scope.select_students = [];
			
			$http({
			  method: 'POST',
			  url: 'handlers/dtr-students-select.php',
			  data: {sy: scope.studentDtr.sy, level: scope.studentDtr.grade, section: scope.studentDtr.section}
			}).then(function mySucces(response) {					
				
				scope.select_students = angular.copy(response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
		};
		
		self.studentsSelect = function(scope) {

			if (scope.studentDtr.by == 'Section') studentsSelect(scope);

			if (scope.studentDtr.by == 'SF2') if (scope.studentDtr.grade !== undefined) scope.sections = scope.studentDtr.grade.sections;

		};

		self.sectionStudentSelect = function(scope) {
			
			if (scope.studentDtr.select_student === undefined) return;
			
			scope.studentDtr.fullname = scope.studentDtr.select_student['fullname'];
			scope.studentDtr.sgrade = scope.studentDtr.select_student['grade'];
			scope.studentDtr.ssection = scope.studentDtr.select_student['section'];
			scope.studentDtr.id = scope.studentDtr.select_student['id'];
			scope.studentDtr.rfid = scope.studentDtr.select_student['rfid'];			
			
		};
		
		self.studentsSuggest = function(scope) {
			
			scope.studentDtr.fullname = '';			
			
			if (scope.studentDtr.by == 'Individual_Student') {
				
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
			
			if (scope.studentDtr.by == 'Section') studentsSelect(scope);			
			
		};
		
		self.dtr = function(scope,opt) {				
			
			if (scope.$id > 2) scope = scope.$parent;
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.view_dtr)) return;
			
			if (opt) if (!access.has(scope,scope.module.id,scope.module.privileges.re_analyze_dtr)) return;
			
			switch (scope.studentDtr.by) {
				
				case 'Individual_Student':
				
					if (scope.studentDtr.id == 0) {
						pnotify.show('error','Notification','Please select student');
						return;
					};

					if (scope.studentDtr.ssection == '') {
						pnotify.show('error','Notification','Student has no defined section, please set it first in the student current enrollment');
						return;
					};				
				
					individual(scope);
				
				break;
				
				case 'SF2':
						
					if (scope.studentDtr.grade.id == 0) {
						pnotify.show('error','Notification','Please select grade');
						return;
					};

					if (scope.studentDtr.section.id == 0) {
						pnotify.show('error','Notification','Please select section');
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
							
							$('#dtr-sections').append('<br><span>Generating excel report please wait...</span>');
							
							$http({
							  method: 'POST',
							  url: 'excel/form2excel.php',
							  data: scope.studentDtr
							}).then(function mySucces(response) {				

								$('#dtr-sections').append('<span>...done</span><br>');								
								$('#dtr-sections').append('<span class="btn btn-default"><a href="'+response.data+'">Download</a></span>');								
								
							}, function myError(response) {
								 
							  // error
								
							});
							
						};
						
					};
				
				break;
				
				case 'Section':
				
					// if (scope.studentDtr.id == 0) {
						// pnotify.show('error','Notification','Please select student');
						// return;
					// };

					// if (scope.studentDtr.ssection == '') {
						// pnotify.show('error','Notification','Student has no defined section, please set it first in the student current enrollment');
						// return;
					// };				
				
					// individual(scope);
					
					if (scope.studentDtr.grade.id == 0) {
						pnotify.show('error','Notification','Please select grade');
						return;
					};

					if (scope.studentDtr.section.id == 0) {
						pnotify.show('error','Notification','Please select section');
						return;
					};					
					
					$http({
					  method: 'POST',
					  url: 'handlers/detailed-students-attendance.php',
					  data: scope.studentDtr
					}).then(function mySucces(response) {				

						section(scope,response.data);
						
					}, function myError(response) {
						 
					  // error
						
					});					
				
				break;
				
			};

			function individual(scope) {
				
				var onOk = function() {
				
					blockUI.show('Analyzing dtr please wait...');				
				
					scope.studentDtr.option = opt;
				
					scope.views.panel_title = scope.studentDtr.fullname+': '+scope.studentDtr.sgrade+' '+scope.studentDtr.ssection+' ('+scope.studentDtr.month.description+' '+scope.studentDtr.year+')';
				
					scope.dtr.student = [];			
				
					$http({
					  method: 'POST',
					  url: 'handlers/dtr-student.php',
					  data: scope.studentDtr
					}).then(function mySucces(response) {					
						
						scope.dtr.student = angular.copy(response.data);
						blockUI.hide();
						
					}, function myError(response) {
						 
						blockUI.hide();
						
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
				
			};
			
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
			  // url: 'handlers/logs-fetch-students.php',
			  url: 'http://192.168.1.30/logs-fetch-students.php',
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
			  data: {dtr: 'dtr_students', manual: 'students_manual_logs', id: scope.studentDtr.id, rfid: row.rfid, date: row.ddate, dtr_id: row.id}
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
			  data: {dtr: "dtr_students", manual: "students_manual_logs", student_id: scope.studentDtr.id, day: scope.dtr_day}
			}).then(function mySucces(response) {
				
				self.dtr(scope,false);
				
			}, function myError(response) {				 
				
			});		
			
		};
		
		self.dtrBySelected = function(scope) {
			
			scope.views.panel_title = '';
			
			$('#x_content').html('');			
			
		};
		
		function section(scope,attendance) {

			var doc = new jsPDF({
				orientation: 'landscape',
				unit: 'pt',
				format: [612, 936]
			});
			
			var totalPagesExp = "{total_pages_count_string}";
			
			var pageContent = function (data) {
				// HEADER
				// doc.addImage('data:image/png;base64,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', 'JPEG', 820, 15, 80, 80);
				doc.addImage('data:image/png;base64,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', 'PNG', 820, 20, 100, 50);
				
				doc.setTextColor(40,40,40);	
				
				doc.setFontSize(12);
				doc.setFontType('bold');
				doc.myText('LORD OF ZION DIVINE SCHOOL',{align: "center"},0,30);

				doc.setFontSize(10);			
				doc.setFontType('normal');
				doc.myText('Sitio Paratong, Poblacion, Bacnotan, La Union',{align: "center"},0,45);

				// doc.myText('',{align: "center"},0,112);				
				doc.text(35, 112, 'Grade Level:');
				doc.setFontType('bold');
				doc.text(95, 112, scope.studentDtr.grade.description);
				doc.setFontType('normal');
				doc.text(200, 112, 'Section:');
				doc.setFontType('bold');
				doc.text(240, 112, scope.studentDtr.section.description);
				doc.setFontType('normal');
				doc.text(370, 112, 'SY:');
				doc.setFontType('bold');
				doc.text(390, 112, scope.studentDtr.sy.school_year);
				doc.setFontType('normal');				
				doc.text(475, 112, 'Month:');
				doc.setFontType('bold');
				doc.text(510, 112, scope.studentDtr.month.description);
				doc.setFontType('normal');
				doc.text(585, 112, 'Year:');
				doc.setFontType('bold');
				doc.text(615, 112, scope.studentDtr.year.toString());

				doc.setFontSize(12);
				doc.setFontType('bold');
				doc.myText("Detailed Attendance Report",{align: "center"},0,75);
				
				// FOOTER
				var str = "Page " + data.pageCount;
				// Total page number plugin only available in jspdf v1.0+
				if (typeof doc.putTotalPages === 'function') {
					str = str + " of " + totalPagesExp;
				}
				doc.setFontSize(10);
				doc.setFontType('normal');				
				doc.text(str, data.settings.margin.left, doc.internal.pageSize.height - 10);
			};			

			var first_columns = attendance.first.headers;
			var first_rows = attendance.first.students;

			doc.autoTable(first_columns, first_rows, {			
				// tableLineColor: [189, 195, 199],
				// tableLineWidth: 0.75,
				addPageContent: pageContent,				
				margin: {top: 118, left: 30, right: 30, bottom: 140},
				tableWidth: 'wrap',
				showHeader: 'everyPage',		
				// columnStyles: {
					// no: {columnWidth: 50},
					// school_id: {columnWidth: 110},
					// fullname: {columnWidth: 100},
					// gender: {columnWidth: 60},
				// },
				columnStyles: attendance.first.columnStyles,
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.50,
					cellPadding: 3
				},
				headerStyles: {
					halign: 'center',		
					fillColor: [191, 191, 191],
					textColor: 50,
					fontSize: 8.5,
					overflow: 'linebreak',
				},
				bodyStyles: {
					halign: 'left',
					fillColor: [255, 255, 255],
					textColor: 50,
					fontSize: 8.5,
					overflow: 'linebreak',				
				},
				alternateRowStyles: {
					fillColor: [255, 255, 255]
				}
			});	
			
			doc.addPage();
			
			var second_columns = attendance.second.headers;
			var second_rows = attendance.second.students;
			
			doc.autoTable(second_columns, second_rows, {	
				// tableLineColor: [189, 195, 199],
				// tableLineWidth: 0.75,
				addPageContent: pageContent,				
				margin: {top: 118, left: 30, right: 30, bottom: 140},
				tableWidth: 'wrap',
				showHeader: 'everyPage',		
				// columnStyles: {
					// no: {columnWidth: 50},
					// school_id: {columnWidth: 110},
					// fullname: {columnWidth: 100},
					// gender: {columnWidth: 60},
				// },
				columnStyles: attendance.second.columnStyles,				
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.50,
					cellPadding: 3
				},
				headerStyles: {
					halign: 'center',		
					fillColor: [191, 191, 191],
					textColor: 50,
					fontSize: 8.5,
					overflow: 'linebreak',
				},
				bodyStyles: {
					halign: 'left',
					fillColor: [255, 255, 255],
					textColor: 50,
					fontSize: 8.5,
					overflow: 'linebreak',				
				},
				alternateRowStyles: {
					fillColor: [255, 255, 255]
				}
			});			
			
			// Total page number plugin only available in jspdf v1.0+
			if (typeof doc.putTotalPages === 'function') {
				doc.putTotalPages(totalPagesExp);
			}			
			
			// $timeout(function() {
				var blob = doc.output("blob");
				window.open(URL.createObjectURL(blob));
			// }, 1000);
			
		};
		
	};
	
	return new form();
	
});