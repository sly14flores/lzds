<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$summary = array(
	"levels"=>[],
	"overall"=>[array(
		"level"=>"Overall",
		"total_students"=>"",
		"tuition_fees"=>"",
		"total_collections"=>"",
		"total_balance"=>""
	)]
);

$levels = $con->getData("SELECT id, description level FROM grade_levels");

foreach ($levels as $level) {
	
	$summary['levels'][] = $level;
	
};

$overall_total_students = 0;
$overall_tuition_fees = 0;
$overall_total_collections = 0;
$overall_total_balance = 0;

foreach ($summary['levels'] as $i => $sl) {
	
	$total_students = 0;
	$tuition_fees = 0;
	$total_collections = 0;
	$total_balance = 0;

	$q_total_students = $con->getData("SELECT count(*) total_students FROM enrollments WHERE grade = ".$sl['id']." AND enrollment_school_year = ".$_POST['school_year']);	
	$total_students = (count($q_total_students))?$q_total_students[0]['total_students']:0;
	
	$q_tuition_fees = $con->getData("SELECT SUM(students_fees.amount) tuition_fees FROM students_fees LEFT JOIN fee_items ON students_fees.fee_item_id = fee_items.id LEFT JOIN fees ON fee_items.fee_id = fees.id WHERE fee_items.level = ".$sl['id']." AND fees.school_year = ".$_POST['school_year']);
	$tuition_fees = (count($q_tuition_fees))?$q_tuition_fees[0]['tuition_fees']:0;
	
	$q_total_collections = $con->getData("SELECT SUM(payments.amount) total_collections FROM payments LEFT JOIN enrollments ON payments.enrollment_id = enrollments.id WHERE enrollments.grade = ".$sl['id']." AND enrollments.enrollment_school_year = ".$_POST['school_year']);
	$total_collections = (count($q_total_collections))?$q_total_collections[0]['total_collections']:0;
	
	$total_balance = $tuition_fees - $total_collections;
	
	$overall_total_students += $total_students;
	$overall_tuition_fees += $tuition_fees;
	$overall_total_collections += $total_collections;
	$overall_total_balance += $total_balance;
	
	unset($summary['levels'][$i]["id"]);
	
	$summary['levels'][$i]["total_students"] = number_format($total_students);
	$summary['levels'][$i]["tuition_fees"] = "Php. ".number_format($tuition_fees);
	$summary['levels'][$i]["total_collections"] = "Php. ".number_format($total_collections);
	$summary['levels'][$i]["total_balance"] = "Php. ".number_format($total_balance);
	
};

$summary['overall'][0]["total_students"] = number_format($overall_total_students);
$summary['overall'][0]["tuition_fees"] = "Php. ".number_format($overall_tuition_fees);
$summary['overall'][0]["total_collections"] = "Php. ".number_format($overall_total_collections);
$summary['overall'][0]["total_balance"] = "Php. ".number_format($overall_total_balance);

echo json_encode($summary);

?>