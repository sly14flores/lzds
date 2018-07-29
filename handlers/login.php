<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();
$sql = "SELECT id, IFNULL(CONCAT(nickname, ' ', lastname),'') staff, staff_account_group FROM staffs WHERE is_active = 1 AND username = '$_POST[username]' AND password = '$_POST[password]'";
$account = $con->getData($sql);
if (($con->rows) > 0) {
	session_start();
	$_SESSION['id'] = $account[0]['id'];
	$_SESSION['staff'] = $account[0]['staff'];
	$_SESSION['group'] = $account[0]['staff_account_group'];
	echo json_encode(array("login"=>true));
} else {
	echo json_encode(array("login"=>false));
}

?>