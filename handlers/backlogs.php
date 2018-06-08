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

$manual = array(
	"morning_in"=>array("id"=>0,"value"=>false,"save"=>false),
	"morning_out"=>array("id"=>0,"value"=>false,"save"=>false),
	"afternoon_in"=>array("id"=>0,"value"=>false,"save"=>false),
	"afternoon_out"=>array("id"=>0,"value"=>false,"save"=>false),
);

$logs = array("morning_in","morning_out","afternoon_in","afternoon_out");
$dtr = $con->getData("SELECT * FROM ".$_POST['dtr']." WHERE id = ".$_POST['dtr_id']);
foreach ($logs as $log) {
	
	if (date("H:i:s",strtotime($dtr[0][$log]))=="00:00:00") $manual[$log] = array("id"=>0,"value"=>true,"save"=>false);
	
};

$manual_log_id = array("dtr"=>"staff_id","dtr_students"=>"student_id");

$manual_logs = $con->getData("SELECT * FROM ".$_POST['manual']." WHERE ".$manual_log_id[$_POST['dtr']]." = ".$_POST['id']." AND time_log LIKE '".$_POST['date']."%'");
foreach ($manual_logs as $manual_log) {
	$manual[$manual_log['allotment']] = array("id"=>$manual_log['id'],"value"=>true,"save"=>false);
};

header("Content-type: application/json");
echo json_encode(array("backlogs"=>$backlogs,"disabled"=>$disabled,"manual"=>$manual));

?>