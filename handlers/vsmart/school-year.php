<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db("vsmart_settings");

$check_sy = $con->getData("SELECT setting_value FROM vsmart_settings WHERE id = 1");

$school_year = null;

if (count($check_sy)) {
	
	$school_year = $check_sy[0]['setting_value'];
	
}

echo json_encode(array("school_year"=>$school_year));

?>