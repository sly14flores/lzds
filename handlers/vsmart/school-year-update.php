<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db("vsmart_settings");

$check_sy = $con->getData("SELECT * FROM vsmart_settings WHERE id = 1");

if (count($check_sy)) {
	
	$sy = $con->updateData(array(
		"id"=>1,
		"setting_value"=>$_POST['school_year'],
		"system_log"=>"CURRENT_TIMESTAMP"
	),'id');	
	
} else {
	
	$sy = $con->insertData(array(
		"setting_value"=>$_POST['school_year'],
		"system_log"=>"CURRENT_TIMESTAMP"		
	));
	
}

?>