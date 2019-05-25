<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("school_years");

$_POST['system_log'] = "CURRENT_TIMESTAMP";

if ($_POST['id']) {

	$sy = $con->updateData($_POST,'id');
	
} else {

	unset($_POST['id']);
	$sy = $con->insertData($_POST);

};

?>