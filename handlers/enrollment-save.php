<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("enrollments");

// var_dump($_POST);

if ($_POST['student_enrollment']['id']) { // > 0 - update
	$_POST['student_enrollment']['update_log'] = "CURRENT_TIMESTAMP";
	$enrollment = $con->updateData($_POST['student_enrollment'],'id');
} else { // 0 - insert
	unset($_POST['student_enrollment']['id']);
	$_POST['student_enrollment']['system_log'] = "CURRENT_TIMESTAMP";	
	$_POST['student_enrollment']['grade'] = $_POST['student_enrollment']['grade']['id'];	
	$_POST['student_enrollment']['section'] = $_POST['student_enrollment']['section']['id'];
	$_POST['student_enrollment']['enrollment_school_year'] = $_POST['student_enrollment']['enrollment_school_year']['id'];
	$_POST['student_enrollment']['enrollment_date'] = "CURRENT_TIMESTAMP";		
	$enrollment = $con->insertData($_POST['student_enrollment']);
}

?>