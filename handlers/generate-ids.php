<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("enrollments");

switch ($_POST['method']) {

case "all":

	if ($_POST['i'] == 0) {
		$school_id = date("y").$_POST['cursor'];	
	} else {
		$school_id = (string)(intval($_POST['cursor'])+1);
	};

	$_POST['student']['school_id'] = $school_id;

	$update_student_id = $con->updateData(array("id"=>$_POST['student']['id'],"school_id"=>$school_id,"update_log"=>"CURRENT_TIMESTAMP"),'id');

break;

case "last":

	if (($_POST['student']['school_id']==null)||($_POST['student']['school_id']=="")) {
		
		$school_id = (string)(intval($_POST['cursor'])+1);

		$_POST['student']['school_id'] = $school_id;
		$_POST['student']['cursor'] = $school_id;
	
		$update_student_id = $con->updateData(array("id"=>$_POST['student']['id'],"school_id"=>$school_id,"update_log"=>"CURRENT_TIMESTAMP"),'id');		
		
	} else {
		
		$_POST['student']['cursor'] = $_POST['cursor'];
		
	};

break;

};

echo json_encode($_POST['student']);

?>