<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("payments");

if ($_POST['id']) {
	
	unset($_POST['payment_date_str']);	
	unset($_POST['amount_str']);
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$_POST['payment_date'] = date("Y-m-d",strtotime($_POST['payment_date']));
	$_POST['description'] = $_POST['description']['name'];
	$_POST['payment_month'] = $_POST['payment_month']['no'];	
	$payment = $con->updateData($_POST,'id');
	
	if ($_POST['description']=="voucher") voucher($con,$_POST,false);
	
} else {
	
	unset($_POST['id']);
	unset($_POST['payment_date_str']);
	unset($_POST['amount_str']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$_POST['payment_date'] = "CURRENT_TIMESTAMP";
	$_POST['description'] = $_POST['description']['name'];
	$_POST['payment_month'] = $_POST['payment_month']['no'];
	$payment = $con->insertData($_POST);

	if ($_POST['description']=="voucher") voucher($con,$_POST,true);	
	
}

function voucher($con,$data,$insert) {
	
	$enrollment_id = $data['enrollment_id'];
	
	$con->table = "students_vouchers";
	
	if ($insert) {
	
		$check_voucher = $con->getData("SELECT * FROM students_vouchers WHERE enrollment_id = $enrollment_id");	
		if (count($check_voucher)) {
			$student_voucher = $con->updateData(array("enrollment_id"=>$enrollment_id,"amount"=>$data['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'enrollment_id');
		} else {
			$student_voucher = $con->insertData(array("enrollment_id"=>$enrollment_id,"amount"=>$data['amount'],"system_log"=>"CURRENT_TIMESTAMP"));
		}
	
	} else {
		
		$student_voucher = $con->updateData(array("enrollment_id"=>$enrollment_id,"amount"=>$data['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'enrollment_id');		
		
	}
	
}

?>