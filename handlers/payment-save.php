<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("payments");

if ($_POST['id']) {
	
	unset($_POST['payment_date_str']);	
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$_POST['payment_date'] = "CURRENT_TIMESTAMP";
	$_POST['description'] = $_POST['description']['name'];
	$_POST['payment_month'] = $_POST['payment_month']['no'];	
	$payment = $con->updateData($_POST,'id');
	
} else {
	
	unset($_POST['id']);
	unset($_POST['payment_date_str']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$_POST['payment_date'] = "CURRENT_TIMESTAMP";
	$_POST['description'] = $_POST['description']['name'];
	$_POST['payment_month'] = $_POST['payment_month']['no'];
	$payment = $con->insertData($_POST);		
	
}

?>