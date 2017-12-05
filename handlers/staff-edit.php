<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$staff = $con->getData("SELECT * FROM staffs WHERE id = $_POST[id]");
$schedule = $con->getData("SELECT id, description FROM schedules WHERE id = ".$staff[0]['schedule_id']);

if ($staff[0]['schedule_id'] < 0) {
	$staff[0]['schedule_id'] = array("id"=>-1,"description"=>"Exempted");
} else {
	$staff[0]['schedule_id'] = (count($schedule))?$schedule[0]:array("id"=>0,"description"=>"");
}


if ($staff[0]['birthday'] == "0000-00-00") $staff[0]['birthday'] = null;
if ($staff[0]['birthday'] != null) $staff[0]['birthday'] = date("m/d/Y",strtotime($staff[0]['birthday']));
unset($staff[0]['staff_account_group']);
unset($staff[0]['is_built_in']);
unset($staff[0]['system_log']);
unset($staff[0]['update_log']);

echo json_encode($staff[0]);

?>