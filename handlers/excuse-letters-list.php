<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("excuse_letters");

$letters = $con->getObj(array("student_id"=>$_POST['id']),array("id","student_id",array("letter_sy"=>array("school_years"=>["id","school_year"])),"letter_date_from","letter_date_to","letter_reason","letter_content"));

foreach ($letters as $i => $letter) {
	
	$letters[$i]['letter_date_from'] = date("F j, Y",strtotime($letter['letter_date_from']));
	$letters[$i]['letter_date_to'] = date("F j, Y",strtotime($letter['letter_date_to']));
	
};

echo json_encode($letters);

?>