<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("staffs");

session_start();

$session_staff_id = $_SESSION['id'];

$response = [];

switch ($_POST['q']) {
	
	case "info":
	
	$staff = $con->getData("SELECT username FROM staffs WHERE id = $session_staff_id");
	$response = $staff[0];
	
	break;
	
	case "security":
	
	$staff = $con->getData("SELECT password FROM staffs WHERE id = $session_staff_id");
	$response = $staff[0];	
	
	break;
	
	case "username":
	
	if (!isset($_POST['data']['username'])) $_POST['data']['username'] = "";

	$staff = $con->getData("SELECT * FROM staffs WHERE id != $session_staff_id AND username = '".$_POST['data']['username']."'");

	$response = array("status"=>false);	
	if (count($staff)) $response = array("status"=>true);	
	
	break;
	
	case "update_username":
	
	$response = $con->updateData(array("id"=>$session_staff_id,"username"=>$_POST['data']['username']),'id');	
	
	break;
	
	case "update_security":
	
	$password = $_POST['data']['pw'];
	$response = $con->updateData(array("id"=>$session_staff_id,"password"=>$password),'id');	
	
	break;
	
};

echo json_encode($response);

?>