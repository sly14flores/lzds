<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db($_POST['table']);

$dtr = array(
	"id"=>$_POST['day']['id'],
	"morning_in"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['morning_in']==NULL)?"00:00:00":$_POST['day']['morning_in'])),
	"morning_out"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['morning_out']==NULL)?"00:00:00":$_POST['day']['morning_out'])),
	"afternoon_in"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['afternoon_in']==NULL)?"00:00:00":$_POST['day']['afternoon_in'])),
	"afternoon_out"=>$_POST['day']['ddate']." ".date("H:i:s",strtotime(($_POST['day']['afternoon_out']==NULL)?"00:00:00":$_POST['day']['afternoon_out'])),
	"system_log"=>"CURRENT_TIMESTAMP"
);

$log = $con->updateData($dtr,'id');

# re analyze tardiness

?>