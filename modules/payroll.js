angular.module('payroll-module', ['ui.bootstrap','bootstrap-modal','school-year','window-open-post','block-ui','pnotify-module','jspdf-module','module-access']).factory('form', function($http,$timeout,$compile,bootstrapModal,schoolYear,printPost,blockUI,pnotify,jspdf,access) {
	
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
				payroll_sy: {id: 0, school_year: "SY"},
				option: false		
			};
			scope.payroll.reports = {
				report: "tardiness_awol",				
				month: scope.months[d.getMonth()],
				period: "first",
				payroll_sy: {id: 0, school_year: "SY"},
				option: false		
			};			
			scope.payroll.individual = {
				id: 0,
				school_id: '',
				fullname: '',
				employment_status: '',
				lastname: '',
				firstname: '',
				mi: '',
				month: scope.months[d.getMonth()],
				period: "first",
				payroll_sy: {id: 0, school_year: "SY"},
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
			
			scope.school_years_ = [];			
			
			schoolYear.get(scope);
			
			$timeout(function() {
				
				scope.school_years_.push({id: 0, school_year: "SY"});
				
				angular.forEach(scope.school_years,function(item,i) {

					scope.school_years_.push(item);
					
				});
				
			},1000);

			$timeout(function() {			
			
				$http({
				  method: 'POST',
				  url: 'handlers/current-sy.php'
				}).then(function mySucces(response) {
			
					scope.payroll.all.payroll_sy = response.data;
					scope.payroll.reports.payroll_sy = response.data;
					scope.payroll.individual.payroll_sy = response.data;
					
				}, function myError(response) {
					 
				  // error
					
				});

			}, 1500);
			
		};		
		
		function validate(scope,form) {
			
			var controls = scope.formHolder['form'].$$controls;

			angular.forEach(controls,function(elem,i) {

				if (elem.$$attr.$attr.required) scope.$apply(function() { elem.$touched = elem.$invalid; });
									
			});

			return scope.formHolder['form'].$invalid;
			
		};		
		
		function process(scope) {
			
			scope.sheet.individual.gross_pay = 0;
			scope.sheet.individual.gross_pay_half = 0;
			scope.sheet.individual.total_deductions = 0;		
			scope.sheet.individual.bonuses = 0;	
			
			angular.forEach(scope.sheet.individual.payroll_pays,function(item,i) {
				
				scope.sheet.individual.gross_pay += parseFloat(item.amount);
				
			});

			angular.forEach(scope.sheet.individual.payroll_deductions,function(item,i) {
				
				scope.sheet.individual.total_deductions += parseFloat(item.amount);
				
			});

			angular.forEach(scope.sheet.individual.payroll_bonuses,function(item,i) {
				
				scope.sheet.individual.bonuses += parseFloat(item.amount);
				
			});

			$timeout(function() {
				
				scope.sheet.individual.net_pay = (scope.sheet.individual.gross_pay)/2 - scope.sheet.individual.total_deductions + scope.sheet.individual.bonuses;
				scope.sheet.individual.gross_pay_half = scope.sheet.individual.gross_pay/2;			
				
			},200);
			
		};
		
		self.individual = function(scope,reprocess) {

			if (!access.has(scope,scope.module.id,scope.module.privileges.generate_individual_payroll)) return;		
		
			if (reprocess) if (!access.has(scope,scope.module.id,scope.module.privileges.reproces_individual_payroll)) return;
		
			if (scope.payroll.individual.id == 0) {
				pnotify.show('error','Notification','No staff selected');				
				return;
			};
			
			if ((scope.payroll.individual.employment_status == 'EOC') || (scope.payroll.individual.employment_status == 'Resigned')) {
				pnotify.show('info','Notification','Staff employment status is '+scope.payroll.individual.employment_status);
				return;
			};
			
			var onOk = function() {
			
				scope.sheet.individual = {};	
				scope.sheet.individual.id = 0;
				scope.sheet.individual.payroll_pays = [];
				scope.sheet.individual.payroll_deductions = [];
				scope.sheet.individual.payroll_bonuses = [];

				scope.views.panel_title = scope.payroll.individual.fullname+' ('+scope.periods[scope.payroll.individual.period]+', '+scope.payroll.individual.month.description+' '+scope.payroll.individual.payroll_sy.school_year+')';				
				
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
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.update_payroll_info)) return;			
			
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
			scope.payroll.individual.school_id = item['school_id'];
			scope.payroll.individual.employment_status = item['employment_status'];
			scope.payroll.individual.lastname = item['lastname'];
			scope.payroll.individual.firstname = item['firstname'];
			scope.payroll.individual.mi = item['mi'];

		};
		
		self.staffSelected = function(scope) {
			
			var staff = scope.payroll.individual.staff;
			
			scope.payroll.individual.fullname = staff['fullname'];
			scope.payroll.individual.id = staff['id'];
			scope.payroll.individual.school_id = staff['school_id'];
			scope.payroll.individual.employment_status = staff['employment_status'];
			scope.payroll.individual.lastname = staff['lastname'];
			scope.payroll.individual.firstname = staff['firstname'];
			scope.payroll.individual.mi = staff['mi'];

		};		
		
		self.print = function(scope) {
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.print_individual_payroll)) return;
			
			var period = {
				first: '15th',
				second: '30th'
			};

			var doc = new jsPDF({
				orientation: 'portrait',
				unit: 'pt',
				format: [792, 612]
			});
			
			doc.addImage('data:image/png;base64,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', 'JPEG', 251, 20, 100, 100);
			
			doc.setTextColor(40,40,40);			
			
			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.myText('LORD OF ZION DIVINE SCHOOL',{align: "center"},0,140);
			
			doc.setFontSize(10);			
			doc.setFontType('normal');
			doc.myText('Sitio Paratong, Poblacion, Bacnotan, La Union',{align: "center"},0,155);
			
			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.myText("INDIVIDUAL TEACHER'S ACCOUNT FORM",{align: "center"},0,185);

			// doc.rect(50, 205, 512, 30);
			doc.rect(50, 205, 512, 405);
			doc.line(50, 235, 562, 235); // Horizontal Divider
			doc.line(120, 205, 120, 235); // Name Divider
			doc.line(470, 205, 470, 235); // ID Divider
			doc.line(470, 220, 562, 220); // ID Verial Divider

			doc.setFillColor(220,220,220);	
			doc.rect(51, 206, 68, 28, 'F');
			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.text(65, 224, 'Name:');
			
			// Staff Name
			doc.text(130, 224, scope.payroll.individual.lastname);			
			doc.text(240, 224, scope.payroll.individual.firstname);			
			doc.text(430, 224,  scope.payroll.individual.mi+'.');			

			doc.setFillColor(220,220,220);			
			doc.rect(471, 206, 90, 13, 'F');				
			doc.text(510, 217, 'ID');
			doc.setFillColor(169,169,169);			
			doc.rect(471, 221, 90, 13, 'F');				
			doc.text(500, 232, scope.payroll.individual.school_id);			
			
			// Basic Pay
			doc.line(50, 245, 400, 245);		
			doc.line(400, 265, 562, 265);
			doc.line(400, 245, 400, 265);
			
			// Text
			doc.text(57, 258, 'Basic Pay');
			doc.text(520, 258, scope.sheet.individual.basic_pay.toString());

			doc.setFontSize(11);			
			doc.setFontType('normal');
			var y = 275;
			angular.forEach(scope.sheet.individual.payroll_pays,function(item,i) {
				if (i == 0) return;
				doc.text(125, y, item.description);
				doc.text(365, y, item.amount.toString());
				y+=13;
			});
			
			// Gross Pay
			doc.line(50, 325, 400, 325);
			doc.line(400, 345, 562, 345);
			doc.line(400, 325, 400, 345);	
	
			// Text
			doc.setFontSize(12);	
			doc.setFontType('bold');
			doc.setFillColor(240,240,240);			
			doc.rect(51, 348, 510, 15, 'F');			
			doc.text(57, 340, 'Gross Pay');
			doc.text(520, 340, scope.sheet.individual.gross_pay.toString());
			doc.text(320, 360, 'Period:');
			doc.text(365, 360, period[scope.sheet.individual.payroll_period]);
			doc.text(520, 360, scope.sheet.individual.gross_pay_half.toString());
			
			// Less
			doc.text(57, 390, 'Less:');
			// Text
			doc.setFontSize(11);		
			doc.setFontType('normal');
			var y = 402;
			angular.forEach(scope.sheet.individual.payroll_deductions,function(item,i) {
				doc.text(125, y, item.description);
				doc.text(365, y, item.amount.toString());
				y+=14;
			});

			// Total Deductions
			doc.line(50, 508, 400, 508);
			doc.line(400, 528, 562, 528);
			doc.line(400, 508, 400, 528);		
			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.text(57, 522, 'Total Deduction');
			// Text
			doc.text(520, 522, scope.sheet.individual.total_deductions.toString());			
			
			// Add
			doc.text(57, 545, 'Add:');
			// Text
			doc.setFontSize(11);
			doc.setFontType('normal');
			var y = 545;
			angular.forEach(scope.sheet.individual.payroll_bonuses,function(item,i) {
				doc.text(125, y, item.description);
				doc.text(365, y, item.amount.toString());
				y+=13;
			});
			
			// Net Pay
			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.text(57, 595, 'Net Pay');
			doc.line(50, 580, 400, 580);
			doc.line(400, 600, 562, 600);
			doc.line(400, 580, 400, 600);			
			// Text
			doc.text(520, 595, scope.sheet.individual.net_pay.toString());
			
			// Acknowledgment
			var columns = [
				{title: "Month", dataKey: "month"},
				{title: period[scope.sheet.individual.payroll_period]+" Date", dataKey: "date"},
				{title: "Amount", dataKey: "amount"},
				{title: "Signature", dataKey: "signature"},
			];
			var rows = [{"month": scope.months[parseInt(scope.sheet.individual.payroll_month)-1]['description'],"date":  "", "amount": "", "signature": ""}];
			doc.autoTable(columns, rows, {
				// tableLineColor: [189, 195, 199],
				// tableLineWidth: 0.75,
				margin: {top: 625, left: 50},
				tableWidth: 500,
				columnStyles: {
					month: {columnWidth: 130},
					date: {columnWidth: 125},
					amount: {columnWidth: 125},
					signature: {columnWidth: 130}
				},
				styles: {
					lineColor: [75, 75, 75],
					lineWidth: 0.50,
					cellPadding: 3
				},
				headerStyles: {
					halign: 'center',		
					fillColor: [191, 191, 191],
					textColor: 50,
					fontSize: 10
				},
				bodyStyles: {
					halign: 'center',
					fillColor: [255, 255, 255],
					textColor: 50,
					fontSize: 10
				},
				alternateRowStyles: {
					fillColor: [255, 255, 255]
				}
			});		
			
			// Signatories
			doc.setFontSize(10);
			doc.setFontType('normal');
			doc.text(57, 680, 'Approved by:');
			doc.myText('Directress',{align: "center"},0,740,false,300);			
			doc.text(340, 680, 'Prepared by:');
			doc.myText('Administrative Officer',{align: "center"},0,740,true,300);

			doc.setFontSize(12);
			doc.setFontType('bold');
			doc.myText('Normita Q. Tria',{align: "center"},0,725,false,300);
			doc.myText('Frederick Q. Tria',{align: "center"},0,725,true,300);
			
			var blob = doc.output("blob");
			window.open(URL.createObjectURL(blob));

		};
		
		self.printAll = function(scope) {			
			
			if (scope.payroll.all.payroll_sy.id == 0) {
				pnotify.show('error','Notification','Please select school year.');				
				return;
			};
			
			if (!access.has(scope,scope.module.id,scope.module.privileges.print_payroll_sheet)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/payroll-all.php',
			  data: scope.payroll.all
			}).then(function mySucces(response) {			
				
				payrollAll(response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			function payrollAll(all) {
				//
				var doc = new jsPDF({
					orientation: 'landscape',
					unit: 'pt',
					format: [612, 936]
				});
				
				var totalPagesExp = "{total_pages_count_string}";
				
				var pageContent = function (data) {
					// HEADER
					doc.addImage('data:image/png;base64,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', 'JPEG', 820, 15, 80, 80);
					
					doc.setTextColor(40,40,40);	
					
					doc.setFontSize(12);
					doc.setFontType('bold');
					doc.myText('LORD OF ZION DIVINE SCHOOL',{align: "center"},0,30);

					doc.setFontSize(10);			
					doc.setFontType('normal');
					doc.myText('Sitio Paratong, Poblacion, Bacnotan, La Union',{align: "center"},0,45);

					doc.text(35, 112, 'Period Covered:');		
					doc.text(125, 112, all.info.period); // period	
					doc.text(350, 112, 'SY:');
					doc.text(380, 112, all.info.sy); // sy
					doc.text(680, 112, 'Date Issued:');
					doc.text(750, 112, all.info.date); // date
					
					doc.setFontSize(12);
					doc.setFontType('bold');
					doc.myText("PAYROLL SHEET",{align: "center"},0,75);
					
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

				var columns = [
					{title: "ID", dataKey: "id"},
					{title: "Last Name", dataKey: "lastname"},
					{title: "First Name", dataKey: "firstname"},
					{title: "MI", dataKey: "mi"},
					{title: "Basic Pay", dataKey: "basic_pay"},
					{title: "COLA", dataKey: "cola"},
					{title: "Gross Pay", dataKey: "gross_pay"},
					{title: "SSS Premium", dataKey: "sss_premium"},
					{title: "HDMF Premium", dataKey: "hdmf_premium"},
					{title: "PHIC Premium", dataKey: "phic_premium"},
					{title: "Tax Withheld", dataKey: "tax"},
					{title: "Salary Loan", dataKey: "salary_loan"},
					{title: "Other Loans", dataKey: "other_loans"},
					{title: "Total Deduction", dataKey: "deduction"},
					{title: "Incentive Pay", dataKey: "incentive"},
					{title: "Net Pay", dataKey: "net_pay"},
					{title: "Signature", dataKey: "signature"},
				];
				var rows = all.row;
				doc.autoTable(columns, rows, {			
					// tableLineColor: [189, 195, 199],
					// tableLineWidth: 0.75,
					addPageContent: pageContent,				
					margin: {top: 118, left: 30, right: 30, bottom: 140},
					tableWidth: 875,
					showHeader: 'everyPage',		
					columnStyles: {
						id: {columnWidth: 50},
						lastname: {columnWidth: 90},
						firstname: {columnWidth: 90},
						mi: {columnWidth: 30},
						basic_pay: {columnWidth: 45},
						cola: {columnWidth: 45},
						gross_pay: {columnWidth: 45},
						sss_premium: {columnWidth: 45},
						hdmf_premium: {columnWidth: 45},
						phic_premium: {columnWidth: 45},
						tax: {columnWidth: 45},
						salary_loan: {columnWidth: 45},
						other_loans: {columnWidth: 45},
						deduction: {columnWidth: 50},
						incentive: {columnWidth: 45},
						net_pay: {columnWidth: 45},
						signature: {columnWidth: 70},
					},
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
						halign: 'center',
						fillColor: [255, 255, 255],
						textColor: 50,
						fontSize: 8.5
					},
					alternateRowStyles: {
						fillColor: [255, 255, 255]
					}
				});		
				
				// Total page number plugin only available in jspdf v1.0+
				if (typeof doc.putTotalPages === 'function') {
					doc.putTotalPages(totalPagesExp);
				}
				
				// Totals
				doc.setFontSize(8.5);
				doc.setFontType('normal');
				doc.text(190, 480, 'TOTALS:');

				doc.setLineWidth(1);
				doc.line(282, 485, 830, 485);
				doc.text(287, 480, all.total.basic_pay); // Basic Pay		
				doc.text(335, 480, all.total.cola); // COLA
				doc.text(380, 480, all.total.gross_pay); // Gross Pay
				doc.text(428, 480, all.total.sss); // SSS
				doc.text(472, 480, all.total.hdmf); // HDMF
				doc.text(516, 480, all.total.phic); // PHIC
				doc.text(565, 480, all.total.tax); // Tax
				doc.text(610, 480, all.total.salary_loan); // Salary Loan
				doc.text(652, 480, all.total.other_loans); // Other Loans
				doc.text(695, 480, all.total.total_deductions); // Total Deductions
				doc.text(747, 480, all.total.incentive); // Incentive
				doc.text(787, 480, all.total.net_pay); // Net Pay
				
				// Signatories
				doc.setFontSize(10);
				doc.setFontType('normal');
				doc.text(85, 550, 'Approved by:');
				doc.myText('Directress',{align: "center"},0,590,false,468);			
				doc.text(545, 550, 'Prepared by:');
				doc.myText('Administrative Officer',{align: "center"},0,590,true,468);

				doc.setFontSize(12);
				doc.setFontType('bold');
				doc.myText('Normita Q. Tria',{align: "center"},0,570,false,468);
				doc.myText('Frederick Q. Tria',{align: "center"},0,570,true,468);
						
				var blob = doc.output("blob");
				window.open(URL.createObjectURL(blob));
				//
			};
			
		};
		
		self.printReports = function(scope) {		
			
			if (scope.payroll.reports.payroll_sy.id == 0) {
				pnotify.show('error','Notification','Please select school year.');				
				return;
			};			
			
			// if (!access.has(scope,scope.module.id,scope.module.privileges.print_payroll_reports)) return;
			
			$http({
			  method: 'POST',
			  url: 'handlers/payroll-reports.php',
			  data: scope.payroll.reports
			}).then(function mySucces(response) {		
				
				payrollReports(response.data);
				
			}, function myError(response) {
				 
			  // error
				
			});			
			
			function payrollReports(reports) {
				//
				var doc = new jsPDF({
					orientation: 'portrait',
					unit: 'pt',
					format: [612, 936]
				});

				var totalPagesExp = "{total_pages_count_string}";
				
				var pageContent = function (data) {
					// HEADER
					doc.addImage('data:image/png;base64,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', 'JPEG', 820, 15, 80, 80);
					
					doc.setTextColor(40,40,40);	
					
					doc.setFontSize(12);
					doc.setFontType('bold');
					doc.myText('LORD OF ZION DIVINE SCHOOL',{align: "center"},0,30);

					doc.setFontSize(10);			
					doc.setFontType('normal');
					doc.myText('Sitio Paratong, Poblacion, Bacnotan, La Union',{align: "center"},0,45);

					doc.text(35, 112, 'Period Covered:');
					doc.text(125, 112, reports.info.period); // period
					doc.myText('SY:'+reports.info.sy,{align: "center"},0,112);
					doc.text(425, 112, 'Date Issued:');
					doc.text(485, 112, reports.info.date); // date
	
					doc.setFontSize(12);
					doc.setFontType('bold');
					doc.myText("Tardiness/AWOL",{align: "center"},0,75);
					
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

				var columns = [
					{title: "ID", dataKey: "id"},
					{title: "Last Name", dataKey: "lastname"},
					{title: "First Name", dataKey: "firstname"},
					{title: "MI", dataKey: "mi"},
					{title: "Tardiness", dataKey: "tardiness"},
					{title: "AWOL", dataKey: "awol"},
				];
				var rows = reports.row;
				doc.autoTable(columns, rows, {			
					// tableLineColor: [189, 195, 199],
					// tableLineWidth: 0.75,
					addPageContent: pageContent,				
					margin: {top: 118, left: 30, right: 30, bottom: 140},
					tableWidth: 875,
					showHeader: 'everyPage',		
					columnStyles: {
						id: {columnWidth: 50},
						lastname: {columnWidth: 110},
						firstname: {columnWidth: 120},
						mi: {columnWidth: 60},
						tardiness: {columnWidth: 100},
						awol: {columnWidth: 100},
					},
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
						halign: 'center',
						fillColor: [255, 255, 255],
						textColor: 50,
						fontSize: 8.5
					},
					alternateRowStyles: {
						fillColor: [255, 255, 255]
					}
				});		
				
				// Total page number plugin only available in jspdf v1.0+
				if (typeof doc.putTotalPages === 'function') {
					doc.putTotalPages(totalPagesExp);
				}
				
				// Totals
				// doc.setFontSize(8.5);
				// doc.setFontType('normal');
				// doc.text(190, 480, 'TOTALS:');

				// doc.setLineWidth(1);
				// doc.line(282, 485, 830, 485);
				// doc.text(287, 480, reports.total.tardiness); // Tardiness	
				// doc.text(335, 480, reports.total.awol); // AWOL
						
				var blob = doc.output("blob");
				window.open(URL.createObjectURL(blob));
				//
			};
			
		};		
		
	};
	
	return new form();
	
});