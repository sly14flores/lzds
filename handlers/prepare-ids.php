<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("grade_levels");
$level = $con->get(array("id"=>$_POST['level']),["base_id"]);

if ($_POST['method'] == "all") {

	$start = $level[0]['base_id'];

} else {
	
	$enrollees = $_POST['enrollees'];
	
	$ids = [];
	
	foreach ($enrollees as $i => $enrollee) {
		
		$enrollees[$i]['school_id'] = (($enrollee['school_id']==null)||($enrollee['school_id']==""))?0:intval($enrollee['school_id']);
		$ids[] = $enrollees[$i]['school_id'];
		
	};
	
	array_multisort($ids, SORT_DESC, $enrollees);

	if ($enrollees[0]['school_id'] == 0) {
		$start = date("y").$level[0]['base_id'];
		$start = intval($start)-1;
		$start = (string)$start;
	} else {
		$start = (string)($enrollees[0]['school_id']);
	};
	
};

$response = array("start"=>$start);

echo json_encode($response);

?>