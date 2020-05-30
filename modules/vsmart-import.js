angular.module('vsmart-import',['ui.bootstrap','bootstrap-modal','x-panel-module','pnotify-module','block-ui','school-year','window-open-post','module-access']).factory('vsmartSimport',function($rootScope,$http,$timeout,schoolYear,blockUI,pnotify) {
	
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
				pnotify.show('danger','Notification','No data to analyze, please upload csv file');								
			};
			
		};
		
		self.importNew = function() {
			
			let file = $('#upload-csv')[0].files[0];
			
			if (file === undefined) {
				pnotify.show('danger','Notification','No data to import, please upload csv file');								
			};			
			
		};
		
		self.importOld = function() {
			
			let file = $('#upload-csv')[0].files[0];
			
			if (file === undefined) {
				pnotify.show('danger','Notification','No data to import, please upload csv file');								
			};			
			
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
			
			element.bind('change', function() {
				
				let file = element[0].files[0];

				validateFile(scope,file);

			});

		}
	}
	
});