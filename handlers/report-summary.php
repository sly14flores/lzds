<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$and = array();

switch ($_POST['coverage']) {

	case "Daily":

		$and = array(
			"enrollments"=>" AND enrollment_school_year = ".$_POST['school_year']['id']." AND enrollment_date = '".date("Y-m-d",strtotime($_POST['date']))."'",
			"total_collections"=>" AND payment_date = '".date("Y-m-d",strtotime($_POST['date']))."'"
		);

	break;
	
	case "Weekly":

		$and = array(
			"enrollments"=>" AND enrollment_school_year = ".$_POST['school_year']['id']." AND (enrollment_date >= '".date("Y-m-d",strtotime($_POST['week_from']))."' AND enrollment_date <= '".date("Y-m-d",strtotime($_POST['week_to']))."')",
			"total_collections"=>" AND (payment_date >= '".date("Y-m-d",strtotime($_POST['week_from']))."' AND payment_date <= '".date("Y-m-d",strtotime($_POST['week_to']))."')"
		);

	break;
	
	case "Monthly":

		$ym = $_POST['year']."-".$_POST['month']['id'];

		$and = array(
			"enrollments"=>" AND enrollment_school_year = ".$_POST['school_year']['id']." AND enrollment_date LIKE '$ym%'",
			"total_collections"=>" AND payment_date LIKE '$ym%'"
		);

	break;
	
	case "Annually":

		$y = $_POST['year'];
	
		$and = array(
			"enrollments"=>" AND enrollment_school_year = ".$_POST['school_year']['id']." AND enrollment_date LIKE '$y%'",
			"total_collections"=>" AND payment_date LIKE '$y%'"
		);
		
	break;
	
	case "SY":
	
		$and = array(
			"enrollments"=>" AND enrollment_school_year = ".$_POST['school_year']['id'],
			"total_collections"=>""
		);	
	
	break;

};

$summary = array(
	"levels"=>[],
	"overall"=>[array(
		"level"=>"Overall",
		"total_students"=>"",
		"tuition_fees"=>"",
		"tuition_discounts"=>"",
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
$overall_discounts = 0;
$overall_total_collections = 0;
$overall_total_balance = 0;

foreach ($summary['levels'] as $i => $sl) {
	
	$total_students = 0;
	$tuition_fees = 0;
	$discounts = 0;
	$total_collections = 0;
	$total_balance = 0;

	$sql = "SELECT id FROM enrollments WHERE grade = ".$sl['id'].$and['enrollments'];
	$enrollments = $con->getData($sql);
	$total_students = count($enrollments);

	if (count($enrollments)) {

		$students = [];
		
		foreach ($enrollments as $enrollment) {
			
			$students[] = $enrollment['id'];
			
		};
	
		$ids = implode(",",$students);

		$q_tuition_fees = $con->getData("SELECT SUM(students_fees.amount) tuition_fees FROM students_fees WHERE enrollment_id IN ($ids)");
		$tuition_fees = (count($q_tuition_fees))?$q_tuition_fees[0]['tuition_fees']:0;

		$q_discounts = $con->getData("SELECT SUM(amount) discounts FROM students_discounts WHERE enrollment_id IN ($ids)");
		$discounts = (count($q_discounts))?$q_discounts[0]['discounts']:0;

		$q_total_collections = $con->getData("SELECT SUM(payments.amount) total_collections FROM payments WHERE enrollment_id IN ($ids) ".$and['total_collections']);
		$total_collections = (count($q_total_collections))?($q_total_collections[0]['total_collections']):0;
		
	};

	$total_balance = $tuition_fees - $discounts - $total_collections;

	$overall_total_students += $total_students;
	$overall_tuition_fees += $tuition_fees;
	$overall_discounts += $discounts;
	$overall_total_collections += $total_collections;
	$overall_total_balance += $total_balance;
	
	unset($summary['levels'][$i]["id"]);
	
	$summary['levels'][$i]["total_students"] = number_format($total_students);
	$summary['levels'][$i]["tuition_fees"] = "Php. ".number_format($tuition_fees);
	$summary['levels'][$i]["discounts"] = "Php. ".number_format($discounts);
	$summary['levels'][$i]["total_collections"] = "Php. ".number_format($total_collections);
	$summary['levels'][$i]["total_balance"] = "Php. ".number_format($total_balance);
	
};

$summary['overall'][0]["total_students"] = number_format($overall_total_students);
$summary['overall'][0]["tuition_fees"] = "Php. ".number_format($overall_tuition_fees);
$summary['overall'][0]["discounts"] = "Php. ".number_format($overall_discounts);
$summary['overall'][0]["total_collections"] = "Php. ".number_format($overall_total_collections);
$summary['overall'][0]["total_balance"] = "Php. ".number_format($overall_total_balance);

echo json_encode($summary);

?>