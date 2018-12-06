angular.module('dashboard-module', ['module-access','school-year','pnotify-module','block-ui']).factory('dashboard', function($compile,$http,$timeout,access,schoolYear,pnotify,blockUI) {

	function dashboard() {

		var self = this;

		self.data = function(scope) {

			scope.formHolder = {};
			scope.views.title = 'Dashboard';
			
			schoolYear.get(scope);			
			
			scope.filter = {};
			
			$timeout(function() {
				
				scope.filter.from = scope.school_years[0];
				scope.filter.to = scope.school_years[scope.school_years.length-1];
				
			}, 500);
			
			$timeout(function() {							
				
				self.filter(scope);
				
			}, 1000);			

		};
		
		function validate(scope) {

			var controls = scope.formHolder.school_year.$$controls;
			
			angular.forEach(controls,function(elem,i) {

				if (elem.$$attr.$attr.required) elem.$touched = elem.$invalid;
									
			});

			return scope.formHolder.school_year.$invalid;			
			
		};		
		
		self.filter = function(scope) {

			if (validate(scope)) {

				pnotify.show('danger','Notification','Please fill up from and to school years.');
				return false;				

			};
			
			blockUI.show();			
			
			$http({
				method: 'POST',
				url: 'handlers/dashboard.php',
				data: scope.filter
			}).then(function success(response) {
				
				blockUI.hide();
				
				$('#dashboard').load('html/dashboard.html',function() {
					
					populationBarChart(response.data.students_population);					
					populationBarChartGender(response.data.students_population_gender);	
					
					scope.statistics = response.data.statistics;
					
					doughnut(response.data.statistics.gender);
					
					$timeout(function() {
						$compile($('#dashboard')[0])(scope);
					}, 500);
					
				});
				
			}, function error(response) {
				
			});
			
		};
		
		function populationBarChart(data) {
			
			if ($('#populationBarChart').length) {				
			
			  var ctx = document.getElementById("populationBarChart");
			  var populationBarChart = new Chart(ctx, {
				type: 'bar',
				data: data,
				options: {
				  scales: {
					xAxes: [
					
					],
					yAxes: [{
					  ticks: {
						beginAtZero: false
					  }
					}]
				  }
				}
			  });

			};			
			
		};
		
		function populationBarChartGender(data) {
			
			if ($('#populationBarChartGender').length) {
			
			  var ctx = document.getElementById("populationBarChartGender");
			  var populationBarChartGender = new Chart(ctx, {
				type: 'bar',
				data: data,
				options: {
				  scales: {
					xAxes: [
					
					],
					yAxes: [{
					  ticks: {
						beginAtZero: false
					  }
					}]
				  }
				}
			  });

			};			
			
		};
		
		function doughnut(gender) {

			$timeout(function() {
		
				angular.forEach(gender, function(item,i) {
					
					if ($('#canvasDoughnut'+i).length) {

					  var ctx = document.getElementById("canvasDoughnut"+i);
					  console.log(ctx);
					  var data = {
						labels: [
						  "Male",
						  "Female",
						],
						datasets: [{
						  data: item.data,
						  backgroundColor: [
							"#34abe8",
							"#dd34e8",
						  ],
						  hoverBackgroundColor: [
							"#B370CF",
							"#B370CF",
						  ]

						}]
					  };

					  var canvasDoughnut = new Chart(ctx, {
						type: 'doughnut',
						tooltipFillColor: "rgba(51, 51, 51, 0.55)",
						data: data
					  });
					 
					};				
					
				});
			
			}, 1000);

		};
		

	};

	return new dashboard();

});