<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("enrollments");

if ($_POST['student_enrollment']['id']) { // > 0 - update
	$_POST['student_enrollment']['update_log'] = "CURRENT_TIMESTAMP";
	$_POST['student_enrollment']['grade'] = $_POST['student_enrollment']['grade']['id'];
	$_POST['student_enrollment']['section'] = (isset($_POST['student_enrollment']['section']))?$_POST['student_enrollment']['section']['id']:NULL;
	$_POST['student_enrollment']['enrollment_school_year'] = $_POST['student_enrollment']['enrollment_school_year']['id'];
	$enrollment = $con->updateData($_POST['student_enrollment'],'id');
	$enrollment_id = $_POST['student_enrollment']['id'];
	$con->table = "students_fees";
	foreach ($_POST['enrollment_fees'] as $key => $fee) {
		if ($fee['id'] > 0) {
			$data = array("id"=>$fee['id'],"enrollment_id"=>$enrollment_id,"fee_item_id"=>$fee['fee_item_id'],"amount"=>$fee['amount'],"update_log"=>"CURRENT_TIMESTAMP");
			$student_fee = $con->updateData($data,'id');
		} else {
			$data = array("enrollment_id"=>$enrollment_id,"fee_item_id"=>$fee['fee_item_id'],"amount"=>$fee['amount'],"system_log"=>"CURRENT_TIMESTAMP");
			$student_fee = $con->insertData($data);			
		}
	}
	// discount
	$con->table = "students_discounts";
	$check_discount = $con->getData("SELECT * FROM students_discounts WHERE enrollment_id = $enrollment_id");
	if (count($check_discount)) {
		$student_discount = $con->updateData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['discount'],"update_log"=>"CURRENT_TIMESTAMP"),'enrollment_id');
	} else {
		$student_discount = $con->insertData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['discount'],"system_log"=>"CURRENT_TIMESTAMP"));			
	}
	// voucher
	$con->table = "students_vouchers";	
	if ($_POST['details']['voucher']['enable']) {
		$check_voucher = $con->getData("SELECT * FROM students_vouchers WHERE enrollment_id = $enrollment_id");
		if (count($check_voucher)) {
			$student_voucher = $con->updateData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['voucher']['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'enrollment_id');		
		} else {
			$student_voucher = $con->insertData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['voucher']['amount'],"system_log"=>"CURRENT_TIMESTAMP"));
		};
	} else {
		$delete = $con->deleteData(array("enrollment_id"=>$enrollment_id));			
	};
} else { // 0 - insert
	unset($_POST['student_enrollment']['id']);
	$_POST['student_enrollment']['system_log'] = "CURRENT_TIMESTAMP";	
	$_POST['student_enrollment']['grade'] = $_POST['student_enrollment']['grade']['id'];	
	$_POST['student_enrollment']['section'] = (isset($_POST['student_enrollment']['section']))?$_POST['student_enrollment']['section']['id']:NULL;
	$_POST['student_enrollment']['enrollment_school_year'] = $_POST['student_enrollment']['enrollment_school_year']['id'];
	$_POST['student_enrollment']['enrollment_date'] = "CURRENT_TIMESTAMP";		
	$enrollment = $con->insertData($_POST['student_enrollment']);
	$enrollment_id = $con->insertId;
	$con->table = "students_fees";
	foreach ($_POST['enrollment_fees'] as $key => $fee) {
		$data = array("enrollment_id"=>$enrollment_id,"fee_item_id"=>$fee['fee_item_id'],"amount"=>$fee['amount'],"system_log"=>"CURRENT_TIMESTAMP");
		$student_fee = $con->insertData($data);
	}
	$con->table = "students_discounts";		
	$student_discount = $con->insertData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['discount'],"system_log"=>"CURRENT_TIMESTAMP"));	
	// voucher
	if ($_POST['details']['voucher']['enable']) {
		$con->table = "students_vouchers";
		$student_voucher = $con->insertData(array("enrollment_id"=>$enrollment_id,"amount"=>$_POST['details']['voucher']['amount'],"system_log"=>"CURRENT_TIMESTAMP"));
	};
}

echo $enrollment_id;

?>