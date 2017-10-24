<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT * FROM attendances WHERE rfid = '".$_POST['rfid']."' AND time_log LIKE '".$_POST['date']."%'";
$backlogs = $con->getData($sql);

foreach ($backlogs as $key => $backlog) {
	
	$backlogs[$key]['log'] = date("H:i:s",strtotime($backlog['time_log']));
	
}

header("Content-type: application/json");
echo json_encode($backlogs);

?>