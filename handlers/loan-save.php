<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("loans");

if ($_POST['id']) {

	$_POST['update_log'] = "CURRENT_TIMESTAMP";	
	$_POST['loan_date'] = date("Y-m-d",strtotime($_POST['loan_date']));
	$_POST['loan_effectivity'] = date("Y-m-d",strtotime($_POST['loan_effectivity']));
	$payment = $con->updateData($_POST,'id');
	
} else {

	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$_POST['loan_date'] = date("Y-m-d",strtotime($_POST['loan_date']));
	$_POST['loan_effectivity'] = date("Y-m-d",strtotime($_POST['loan_effectivity']));	
	$payment = $con->insertData($_POST);

}

?>