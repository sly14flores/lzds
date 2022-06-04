<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

function getStrand($strands,$name) {

	$strand = array(
		"id" => 0,
		"name" => ""
	);

	foreach ($strands as $_strand) {
		if ($_strand['name'] == $name) {
			$strand = $_strand;
		}
	}

	return $strand;

}

session_start();

$con = new pdo_db();

$enrollment = $con->getData("SELECT id, student_id, school_id, rfid, enrollment_school_year, grade, section, strand FROM enrollments WHERE id = $_POST[id]");
$enrollment_school_year = $con->getData("SELECT id, school_year FROM school_years WHERE id = ".$enrollment[0]['enrollment_school_year']);
$grade = $con->getData("SELECT id, description FROM grade_levels WHERE id = ".$enrollment[0]['grade']);
$sections = $con->getData("SELECT id, description FROM sections WHERE level_id = ".$enrollment[0]['grade']);

if ($enrollment[0]['section'] != null) $section = $con->getData("SELECT id, description FROM sections WHERE id = ".$enrollment[0]['section']);
else $section = array();
$enrollment[0]['enrollment_school_year'] = $enrollment_school_year[0];
$enrollment[0]['grade'] = $grade[0];
$enrollment[0]['grade']['sections'] = $sections;
$enrollment[0]['section'] = (count($section))?$section[0]:array("id"=>0,"description"=>"");

$strands = array(
	array(
		"id" => 1,
		"name" => "STEM"
	),
	array(
		"id" => 2,
		"name" => "GAS"
	),
	array(
		"id" => 3,
		"name" => "ABM"
	),
	array(
		"id" => 4,
		"name" => "HUMSS"
	)
);
$enrollment[0]['strand'] = getStrand($strands,$enrollment[0]['strand']);

$enrollment_fees = $con->getData("SELECT students_fees.id, students_fees.fee_item_id, (SELECT fees.description FROM fees WHERE fees.id = (SELECT fee_items.fee_id FROM fee_items WHERE fee_items.id = students_fees.fee_item_id)) description, students_fees.amount amount FROM students_fees WHERE students_fees.enrollment_id = ".$enrollment[0]['id']);
$discount = $con->getData("SELECT amount FROM students_discounts WHERE enrollment_id = ".$enrollment[0]['id']);
$voucher = $con->getData("SELECT amount FROM students_vouchers WHERE enrollment_id = ".$enrollment[0]['id']);
$details = array(
	"discount"=>count($discount)?$discount[0]['amount']:0,
	"voucher"=>array(
		"enable"=>count($voucher)?true:false,
		"amount"=>count($voucher)?$voucher[0]['amount']:0
	)
);

echo json_encode(array("enrollment"=>$enrollment[0],"enrollment_fees"=>$enrollment_fees,"details"=>$details));

?>