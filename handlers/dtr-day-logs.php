<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("dtr");

$dtr = array(
	"id"=>$_POST['id'],
	"morning_in"=>date("Y-m-d H:i:s",strtotime($_POST['morning_in'])),
	"morning_out"=>date("Y-m-d H:i:s",strtotime($_POST['morning_out'])),
	"afternoon_in"=>date("Y-m-d H:i:s",strtotime($_POST['afternoon_in'])),
	"afternoon_out"=>date("Y-m-d H:i:s",strtotime($_POST['afternoon_out'])),
	"system_log"=>"CURRENT_TIMESTAMP"
);

$log = $con->updateData($dtr,'id');

?>