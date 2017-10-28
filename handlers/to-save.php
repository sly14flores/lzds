<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("travel_orders");

if ($_POST['id']) {

	$_POST['update_log'] = "CURRENT_TIMESTAMP";	
	$_POST['to_date'] = date("Y-m-d",strtotime($_POST['to_date']));
	$payment = $con->updateData($_POST,'id');
	
} else {

	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$_POST['to_date'] = date("Y-m-d",strtotime($_POST['to_date']));
	$payment = $con->insertData($_POST);

}

?>