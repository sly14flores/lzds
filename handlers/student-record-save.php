<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("students_records");

if ($_POST['id']) {

	$_POST['update_log'] = "CURRENT_TIMESTAMP";	
	$payment = $con->updateObj($_POST,'id');
	
} else {

	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$payment = $con->insertObj($_POST);

}

?>