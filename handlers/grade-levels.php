<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, description FROM grade_levels";

$levels = $con->getData($sql);

foreach ($levels as $key => $level) {
	
	$section = $con->getData("SELECT id, description FROM sections WHERE level_id = ".$level['id']);
	$levels[$key]['sections'] = $section;
	
}

echo json_encode($levels);

?>