<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();
$sql = "SELECT id, IFNULL(CONCAT(nickname, ' ', lastname),'') staff, staff_account_group, is_active FROM staffs WHERE username = '$_POST[username]' AND password = '$_POST[password]'";
$account = $con->getData($sql);

$login = array(
	"login"=>false,
	"group"=>false,
	"active"=>false,
);

if (($con->rows) > 0) {
	session_start();
	$_SESSION['id'] = $account[0]['id'];
	$_SESSION['staff'] = $account[0]['staff'];
	$_SESSION['group'] = $account[0]['staff_account_group'];
	$login['login'] = true;
	$login['group'] = (($account[0]['staff_account_group']==null)||($account[0]['staff_account_group']==0))?false:true;
	$login['active'] = (($account[0]['is_active']==null)||($account[0]['is_active']==0))?false:true;
}

echo json_encode($login);

?>