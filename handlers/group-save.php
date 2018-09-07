<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../privileges.php';

session_start();

$con = new pdo_db("groups");

$privileges = [];
if (isset($_POST['privileges'])) {
	
	$arrayHex = new ArrayHex();
		
	$privileges = $arrayHex->toHex(json_encode($_POST['privileges']));
	$_POST['group']['privileges'] = $privileges;
	
};

if ($_POST['group']['id']) {
	$_POST['group']['update_log'] = "CURRENT_TIMESTAMP";
	$group = $con->updateData($_POST['group'],'id');
	$id = $_POST['group']['id'];
} else {
	unset($_POST['group']['id']);
	$_POST['group']['system_log'] = "CURRENT_TIMESTAMP";	
	$group = $con->insertData($_POST['group']);
	$id = $con->insertId;
}

echo $id;

?>