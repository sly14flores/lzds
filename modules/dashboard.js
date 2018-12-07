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
					
					scope.students_population = response.data.students_population;
					scope.students_population_gender = response.data.students_population_gender;
					
					scope.statistics = response.data.statistics;
					
					doughnut(response.data.statistics.gender);
					doughnutByGrades(response.data.statistics.grades);
					
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
							"#707070",
							"#707070",
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
		
		function doughnutByGrades(grades) {
			
			$timeout(function() {
		
				angular.forEach(grades, function(item,i) {
					
					if ($('#gradeDoughnut'+i).length) {

					  var ctx = document.getElementById("gradeDoughnut"+i);

					  var data = {
						labels: [
						  "Nursery",
						  "Kindergarten",
						  "Grade 1",
						  "Grade 2",
						  "Grade 3",
						  "Grade 4",
						  "Grade 5",
						  "Grade 6",
						  "Grade 7",
						  "Grade 8",
						  "Grade 9",
						  "Grade 10",
						  "Grade 11",
						  "Grade 12",
						],
						datasets: [{
						  data: item.data,
						  backgroundColor: [
							"#ffff00", // Nursery
							"#ffc000", // Kindergarten
							"#ff8000", // Grade 1
							"#ff4000", // Grade 2
							"#ff003f", // Grade 3
							"#ff007f", // Grade 4
							"#ff00bf", // Grade 5
							"#c000ff", // Grade 6
							"#8000ff", // Grade 7
							"#4000ff", // Grade 8
							"#007fff", // Grade 9
							"#00bfff", // Grade 10
							"#00ffff", // Grade 11
							"#00ffc0", // Grade 12
						  ],
						  hoverBackgroundColor: [
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
							"#707070",
						  ]

						}]
					  };

					  var gradeDoughnut = new Chart(ctx, {
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