<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$schedule = $con->getData("SELECT * FROM schedules WHERE id = $_POST[id]");
if (count($schedule)) {
	unset($schedule[0]['system_log']);
	unset($schedule[0]['update_log']);
	$schedule[0]['for_student'] = strval($schedule[0]['for_student']);
	$section = $con->getData("SELECT id, description FROM sections WHERE id = ".$schedule[0]['section']);
	$schedule[0]['section']	= (count($section))?$section[0]:array("id"=>0,"description"=>"");
} else {
$schedule[0] = array("id"=>0,"description"=>"","for_student"=>0,"section"=>array("id"=>0,"description"=>""));	
}

$schedule_details = $con->getData("SELECT * FROM schedules_details WHERE schedule_id = $_POST[id]");

foreach ($schedule_details as $key => $schedule_detail) {
	unset($schedule_details[$key]['system_log']);
	unset($schedule_details[$key]['update_log']);
	$schedule_details[$key]['disabled'] = true;
}

header("Content-type: application/json");
echo json_encode(array("schedule"=>$schedule[0],"schedule_details"=>$schedule_details));

?>