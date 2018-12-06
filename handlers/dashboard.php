<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$from = $_POST['from']['id'];
$to = $_POST['to']['id'];

$dashboard = [];

# Statistics
$dashboard['statistics'] = [];
$dashboard['statistics']['all'] = [];

$current = $to;
while ($current >= $from) {

	$sql = "SELECT COUNT(*) population FROM enrollments WHERE enrollment_school_year = $current";
	$enrollments = $con->getData($sql);
	
	$dashboard['statistics']['all'][] = array(
		"no"=>$enrollments[0]['population'],
		"sy"=>schoolYearDescription($con,$current),
		"description"=>"No of Students",
	);

	--$current;

};

$current = $to;
while ($current >= $from) {

	$sql = "SELECT COUNT(*) population FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE students.gender = 'Male' AND enrollments.enrollment_school_year = $current";
	$males = $con->getData($sql);	

	$sql = "SELECT COUNT(*) population FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE students.gender = 'Female' AND enrollments.enrollment_school_year = $current";
	$females = $con->getData($sql);	
	
	$dashboard['statistics']['gender'][] = array(
		"no"=>$enrollments[0]['population'],
		"sy"=>schoolYearDescription($con,$current),
		"data"=>[$males[0]['population'],$females[0]['population']],
	);

	--$current;

};

# Student Population
$dashboard['students_population'] = array(
	"labels"=>[],
	"datasets"=>[
		array(
			"label"=>"No of Students",
			"backgroundColor"=>"#4ea03d",
			"data"=>[]
		),
	],
);

$current = $from;
while ($current <= $to) {

	$dashboard['students_population']['labels'][] = schoolYearDescription($con,$current);

	$sql = "SELECT COUNT(*) population FROM enrollments WHERE enrollment_school_year = $current";
	$enrollments = $con->getData($sql);
	
	$dashboard['students_population']['datasets'][0]['data'][] = $enrollments[0]['population'];

	++$current;

};

# Student Population Gender
$dashboard['students_population_gender'] = array(
	"labels"=>[],
	"datasets"=>[
		array(
			"label"=>"No of Male Students",
			"backgroundColor"=>"#34abe8",
			"data"=>[]
		),
		array(
			"label"=>"No of Female Students",
			"backgroundColor"=>"#dd34e8",
			"data"=>[]
		),		
	],
);

$current = $from;
while ($current <= $to) {

	$dashboard['students_population_gender']['labels'][] = schoolYearDescription($con,$current);

	$sql = "SELECT COUNT(*) population FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE students.gender = 'Male' AND enrollments.enrollment_school_year = $current";
	$males = $con->getData($sql);	
	$dashboard['students_population_gender']['datasets'][0]['data'][] = $males[0]['population']; # Male

	$sql = "SELECT COUNT(*) population FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE students.gender = 'Female' AND enrollments.enrollment_school_year = $current";
	$females = $con->getData($sql);	
	$dashboard['students_population_gender']['datasets'][1]['data'][] = $females[0]['population']; # Female
	
	++$current;

};

function schoolYearDescription($con,$id) {
	
	$sy = $con->getData("SELECT school_year FROM school_years WHERE id = $id");
	
	return $sy[0]['school_year'];
	
};

echo json_encode($dashboard);

?>