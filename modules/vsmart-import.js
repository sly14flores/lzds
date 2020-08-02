angular.module('vsmart-import',['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post','module-access']).factory('vsmartSimport',function($rootScope,$http,$timeout,schoolYear,blockUI,pnotify,bootstrapModal) {
	
	function vsmartSimport() {
		
		let self = this;
		
		self.data = function() {
			
			$rootScope.formHolder = {};
			
			$rootScope.views = {};
			$rootScope.views.title = 'VSmart Import';

			$rootScope.settings = {};
			
			$rootScope.upload = {};
			$rootScope.upload.message = "Upload the downloaded CSV from vsmart then click Analyze";									
			
			schoolYear.getSys().then(response => {
				
				$rootScope.school_years = response.data.school_years;
				currentSy();
				
			}, response => {
				
			});
			
		};
		
		function currentSy() {
			
			$http({
				method: 'POST',
				url: 'handlers/vsmart/school-year.php'
			}).then(response => {

				$rootScope.settings.school_year = response.data.school_year;
				
			}, response => {
			
				
			});				
			
		};
		
		self.updateSy = function() {
			
			if (validate('updateSy')) {
				pnotify.show('danger','Notification','Please select school year');				
				return;
			};
			
			blockUI.show();

			$http({
			  method: 'POST',
			  url: 'handlers/vsmart/school-year-update.php',
			  data: {school_year: $rootScope.settings.school_year}
			}).then(response => {
				
				blockUI.hide();
				
			}, response => {
				
				blockUI.hide();				
				
			});			
			
		};
		
		function validate(form) {

			var controls = $rootScope.formHolder[form].$$controls;
			
			angular.forEach(controls,function(elem,i) {

				// if (elem.$$attr.$attr.required) $rootScope.$apply(function() { elem.$touched = elem.$invalid; });
				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return $rootScope.formHolder[form].$invalid;			

		};
		
		self.analyze = function() {
			
			let file = $('#upload-csv')[0].files[0];
			
			if (file === undefined) {
				
				// pnotify.show('danger','Notification','No data to analyze, please upload csv file');
				
				let onOk = function() {
					
					checkCsv().then(response => {
						
						if (!response.data.exists) pnotify.show('danger','Notification','No enrollment data found on the server to analyze, please upload a csv file');
						else analyze();
						
					}).catch(e => {
						
					});					
					
				};
				
				bootstrapModal.confirm($rootScope,'Analyze vsmart enrollment data','No new csv file uploaded, analyze previously uploaded file instead?',onOk,function() {});
				
			} else {
				
				analyze();
				
			};
			
		};
		
		function checkCsv() {
			
			return $http({
				method: 'POST',
				url: 'handlers/vsmart/check-csv.php'
			});
			
		};
		
		function showAnalyzeModal() {

			bootstrapModal.box3($rootScope,'Analyzing vsmart enrollment data','forms/vsmart-analyze.html');
			
			return new Promise(function(resolve, reject) {
				
				setTimeout(function() {
					
					resolve(true);
					
				},1000);
				
			});
			
		}
		
		function analyze() {
			
			showAnalyzeModal().then(() => {
				
				countRecords().then(response => {
					
					$('#vsmart-analyze-messages').append(response.data);
					
					return getRecords();
					
				}).then(response => { // get records

					analyzeRecord(response.data,1);
					
				});
				
			});
			
			function countRecords() {
				
				return $http({
					method: 'POST',
					url: 'handlers/vsmart/records.php',
					data: {action: 'count'}
				});

			};
			
			function getRecords() {
				
				return $http({
					method: 'POST',
					url: 'handlers/vsmart/records.php',
					data: {action: 'records'}
				});				
				
			};			
			
			function analyzeRecord(records,i) {
				
				$http({
					method: 'POST',
					url: 'handlers/vsmart/records.php',
					data: {action: 'analyze', record: records[i], i: i}
				}).then(response => {
					
					$('#vsmart-analyze-messages').append(response.data);
					
					record();
					
				});
				
				function record() {
					
					if (i<records.length-1) analyzeRecord(records,++i);			
					
				};				
				
			};
			
		};
		
		self.importNew = function() {
			
			
			
		};
		
		self.importOld = function() {
			
			
			
		};		
		
	};
	
	return new vsmartSimport();
	
}).directive('selectCsv',function($rootScope,$parse,pnotify) {
	
	function validateFile(scope,file) {
		
		let s_name = file.name.split(".");
		
		let ext = s_name[1];
		
		if (ext != 'csv') {

			pnotify.show('danger','Notification','Please upload csv file');				
			$('#upload-csv').val(null);
			$rootScope.upload.message = "Upload the downloaded CSV from vsmart then click Analyze";
			scope.$apply();
			
		} else {
			
			uploadCsv(scope,file);
			
		}
		
	};
	
	function uploadCsv(scope,file) {
		
		$rootScope.upload.message = "Uploading...";
		scope.$apply();
		
		let fd = new FormData();
		fd.append('file', file);

        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.open("POST", 'handlers/vsmart/upload.php')
        xhr.send(fd);
		
		// upload progress
		function uploadProgress(evt) {
			scope.$apply(function(){
				let progress = 0;				
				if (evt.lengthComputable) {
					progress = Math.round(evt.loaded * 100 / evt.total);
				} else {
					progress = '';
				}
				$rootScope.upload.message = 'Uploading...'+progress+'%';
			});
		}

		function uploadComplete(evt) {
			/* This event is raised when the server send back a response */
			scope.$apply(function(){			
				$rootScope.upload.message = 'CSV file uploaded, click analyze to analyze data';
			});
		}		

	};

	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			
			element.bind('click', function() {
				
				$rootScope.upload.message = "Upload the downloaded CSV from vsmart then click Analyze";
				scope.$apply();
				$('#upload-csv').val(null);
				
			});
			
			element.bind('change', function() {
				
				let file = element[0].files[0];

				validateFile(scope,file);

			});

		}
	}
	
});