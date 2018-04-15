<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$summary = array(
	"levels"=>[]
);

$levels = $con->getData("SELECT id, description level FROM grade_levels");

foreach ($levels as $level) {
	
	$summary['levels'][] = $level;
	
};

foreach ($summary['levels'] as $i => $sl) {
	
	$total_students = "";
	$tuition_fees = "";
	$total_collections = "";
	$total_balance = "";
	
	$q_total_students = $con->getData("SELECT count(*) total_students FROM enrollments WHERE grade = ".$sl['id']." AND enrollment_school_year = ".$_POST['school_year']);
	
	$total_students = (count($q_total_students))?number_format($q_total_students[0]['total_students']):"";
	
	unset($summary['levels'][$i]["id"]);
	
	$summary['levels'][$i]["total_students"] = $total_students;
	$summary['levels'][$i]["tuition_fees"] = $tuition_fees;
	$summary['levels'][$i]["total_collections"] = $total_collections;
	$summary['levels'][$i]["total_balance"] = $total_balance;
	
};

echo json_encode($summary);

?>