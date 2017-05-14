<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("fees");

if ($_POST['id']) { // > 0 - update
	$fee = $con->updateData($_POST,'id');
} else { // 0 - insert
	unset($_POST['id']);
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$fee = $con->insertData($_POST);
}

?>