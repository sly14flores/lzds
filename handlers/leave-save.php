<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("leaves");

$_POST['with_pay'] = ($_POST['with_pay']=="true")?1:0;

if ($_POST['id']) {

	$_POST['update_log'] = "CURRENT_TIMESTAMP";	
	$_POST['leave_date'] = date("Y-m-d",strtotime($_POST['leave_date']));
	$payment = $con->updateData($_POST,'id');
	
} else {

	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$_POST['leave_date'] = date("Y-m-d",strtotime($_POST['leave_date']));
	$payment = $con->insertData($_POST);

}

?>