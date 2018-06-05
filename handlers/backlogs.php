<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT * FROM attendances WHERE rfid = '".$_POST['rfid']."' AND time_log LIKE '".$_POST['date']."%'";
$backlogs = $con->getData($sql);

foreach ($backlogs as $key => $backlog) {
	
	$backlogs[$key]['log'] = date("H:i:s",strtotime($backlog['time_log']));
	
}

$disabled = array(
	"morning_in"=>true,
	"morning_out"=>true,
	"afternoon_in"=>true,
	"afternoon_out"=>true,
);

$manual_logs = $con->getData("SELECT * FROM students_manual_logs WHERE student_id = ".$_POST['id']." AND time_log LIKE '".$_POST['date']."%'");
foreach ($manual_logs as $manual_log) {
	$disabled[$manual_log['allotment']] = false;
};

header("Content-type: application/json");
echo json_encode(array("backlogs"=>$backlogs,"disabled"=>$disabled));

?>