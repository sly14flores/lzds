<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, description FROM schedules";

$_schedules = $con->getData($sql);
$schedules = [];

$schedules[] = array("id"=>-1,"description"=>"Exempted");
foreach ($_schedules as $i => $s) {
	$schedules[] = $s;
}

header("Content-type: application/json");
echo json_encode($schedules);

?>