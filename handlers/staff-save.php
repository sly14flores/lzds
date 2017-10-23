<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("staffs");

/*
** filters: date
*/

if (isset($_POST['birthday'])) $_POST['birthday'] = date("Y-m-d",strtotime($_POST['birthday']));
	
/*
**
*/

$_POST['schedule_id'] = $_POST['schedule_id']['id'];

if ($_POST['id']) { // > 0 - update
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$staff = $con->updateData($_POST,'id');
} else { // 0 - insert
	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";	
	$staff = $con->insertData($_POST);
}

?>