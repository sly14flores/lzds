<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$fee = $con->getData("SELECT * FROM fees WHERE id = $_POST[id]");
$school_year = $con->getData("SELECT id, school_year FROM school_years WHERE id = ".$fee[0]['school_year']);
$fee[0]['id'] = 0;
$fee[0]['school_year'] = $school_year[0];
$fee_items = $con->getData("SELECT * FROM fee_items WHERE fee_id = $_POST[id]");

unset($fee[0]['system_log']);
unset($fee[0]['update_log']);

foreach ($fee_items as $key => $fee_item) {
	$fee_items[$key]['id'] = 0;
	$fee_items[$key]['fee_id'] = 0;
	$level = $con->getData("SELECT id, description FROM grade_levels WHERE id = $fee_item[level]");
	unset($fee_items[$key]['system_log']);
	unset($fee_items[$key]['update_log']);
	$fee_items[$key]['disabled'] = true;
	$fee_items[$key]['level'] = $level[0];
}

echo json_encode(array("fee"=>$fee[0],"fee_items"=>$fee_items));

?>