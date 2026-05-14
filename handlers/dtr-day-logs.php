<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db($_POST['dtr']);

$dtr = array(
	"id"=>$_POST['day']['id'],
	"morning_in"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['morning_in']==NULL)?"00:00:00":$_POST['day']['morning_in'])),
	"morning_out"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['morning_out']==NULL)?"00:00:00":$_POST['day']['morning_out'])),
	"afternoon_in"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['afternoon_in']==NULL)?"00:00:00":$_POST['day']['afternoon_in'])),
	"afternoon_out"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['afternoon_out']==NULL)?"00:00:00":$_POST['day']['afternoon_out'])),
	"system_log"=>"CURRENT_TIMESTAMP"
);

$log = $con->updateData($dtr,'id');

$con->table = $_POST['manual'];

foreach ($_POST['day']['manual'] as $p => $manual) {

	if (!$manual['save']) continue;
	
	if ($manual['id']) {
		
		$manual_log = $con->updateData(array("id"=>$manual['id'],"time_log"=>$dtr[$p],"update_log"=>"CURRENT_TIMESTAMP"),'id');
		
	} else {
		
		if ($_POST['dtr'] == "dtr") $data = array("staff_id"=>$_POST['staff_id'],"time_log"=>$dtr[$p],"allotment"=>$p,"system_log"=>"CURRENT_TIMESTAMP");
		else $data = array("student_id"=>$_POST['student_id'],"time_log"=>$dtr[$p],"allotment"=>$p,"system_log"=>"CURRENT_TIMESTAMP");
		
		$manual_log = $con->insertData($data);
		
	};

};

?>