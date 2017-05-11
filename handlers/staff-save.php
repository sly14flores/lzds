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

if ($_POST['id']) { // > 0 - update
	$staff = $con->updateData($_POST,'id');
} else { // 0 - insert
	unset($_POST['id']);
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$staff = $con->insertData($_POST);
}

?>