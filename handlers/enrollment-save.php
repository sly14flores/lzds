<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("enrollments");

if ($_POST['student_enrollment']['id']) { // > 0 - update
	$_POST['student_enrollment']['update_log'] = "CURRENT_TIMESTAMP";
	$_POST['student_enrollment']['grade'] = $_POST['student_enrollment']['grade']['id'];
	$_POST['student_enrollment']['section'] = $_POST['student_enrollment']['section']['id'];
	$_POST['student_enrollment']['enrollment_school_year'] = $_POST['student_enrollment']['enrollment_school_year']['id'];
	$enrollment = $con->updateData($_POST['student_enrollment'],'id');
	$enrollment_id = $_POST['student_enrollment']['id'];
	var_dump($_POST['enrollment_fees']);
/* 	$con->table = "students_fees";
	foreach ($_POST['enrollment_fees'] as $key => $fee) {
		$data = array("id"=>$fee['id'],"enrollment_id"=>$enrollment_id,"fee_item_id"=>$fee['fee_item_id'],"amount"=>$fee['amount'],"update_log"=>"CURRENT_TIMESTAMP");
		$student_fee = $con->updateData($data,'id');
	} */
} else { // 0 - insert
	unset($_POST['student_enrollment']['id']);
	$_POST['student_enrollment']['system_log'] = "CURRENT_TIMESTAMP";	
	$_POST['student_enrollment']['grade'] = $_POST['student_enrollment']['grade']['id'];	
	$_POST['student_enrollment']['section'] = $_POST['student_enrollment']['section']['id'];
	$_POST['student_enrollment']['enrollment_school_year'] = $_POST['student_enrollment']['enrollment_school_year']['id'];
	$_POST['student_enrollment']['enrollment_date'] = "CURRENT_TIMESTAMP";		
	$enrollment = $con->insertData($_POST['student_enrollment']);
	$enrollment_id = $con->insertId;
	$con->table = "students_fees";
	foreach ($_POST['enrollment_fees'] as $key => $fee) {
		$data = array("enrollment_id"=>$enrollment_id,"fee_item_id"=>$fee['fee_item_id'],"amount"=>$fee['amount'],"system_log"=>"CURRENT_TIMESTAMP");
		$student_fee = $con->insertData($data);
	}
}

?>