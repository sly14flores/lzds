<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("attendances");

$data = array(
	"attendance_id"=>$_POST['id'],
	"rfid"=>$_POST['rfid'],
	"time_log"=>$_POST['time_log'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

$query = $con->getData("SELECT * FROM attendances WHERE attendance_id = ".$_POST['id']);
if (count($query) == 0) {
	$attendance = $con->insertData($data);
	echo "Imported";
} else {
	echo "Updated";
}

?>