<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, description, section FROM schedules";

$_schedules = $con->getData($sql);
$schedules = [];

$schedules[] = array("id"=>-1,"description"=>"Exempted","section"=>0);
foreach ($_schedules as $i => $s) {
	$schedules[] = $s;
}

foreach ($schedules as $key => $schedule) {
	
	$section = $con->getData("SELECT id, description FROM sections WHERE id = ".$schedule['section']);
	$schedules[$key]['section'] = (count($section))?$section[0]:array("id"=>0,"description"=>"");
	
}

header("Content-type: application/json");
echo json_encode($schedules);

?>