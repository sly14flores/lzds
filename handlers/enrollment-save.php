<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("enrollments");

/*
** filters: date
*/

// if (isset($_POST['birthday'])) $_POST['birthday'] = date("Y-m-d",strtotime($_POST['birthday']));
	
/*
**
*/
var_dump($_POST);
/* if ($_POST['student_enrollment']['id']) { // > 0 - update
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$staff = $con->updateData($_POST,'id');
} else { // 0 - insert
	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";	
	$staff = $con->insertData($_POST);
} */

?>