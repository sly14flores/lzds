<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("payroll_pays");

# payroll_pays
foreach ($_POST['payroll_pays'] as $pay) {
	
	$con->updateData(array("id"=>$pay['id'],"amount"=>$pay['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'id');
	
};

# payroll_deductions
$con->table = "payroll_deductions";
foreach ($_POST['payroll_deductions'] as $pay) {
	
	$con->updateData(array("id"=>$pay['id'],"amount"=>$pay['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'id');
	
};

# payroll_bonuses
$con->table = "payroll_bonuses";
foreach ($_POST['payroll_bonuses'] as $pay) {
	
	$con->updateData(array("id"=>$pay['id'],"amount"=>$pay['amount'],"update_log"=>"CURRENT_TIMESTAMP"),'id');
	
};


?>