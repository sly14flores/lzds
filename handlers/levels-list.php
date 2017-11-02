<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$levels = $con->getData("SELECT id, description FROM grade_levels");

foreach ($levels as $key => $level) {
	
	$sections = $con->getData("SELECT id, description FROM sections WHERE level_id = ".$level['id']);
	
	foreach ($sections as $i => $section) {
		
		$sections[$i]['disabled'] = true;
		
	}
	
	$levels[$key]['sections'] = $sections;
	
}

header("Content-type: application/json");
echo json_encode($levels);

?>