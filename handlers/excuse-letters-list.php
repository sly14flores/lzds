<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("excuse_letters");

$letters = $con->getObj(array("student_id"=>$_POST['id']),array("id","student_id",array("letter_sy"=>array("school_years"=>["id","school_year"])),"letter_reason","letter_content"));
	
$con->table = "excuse_letters_dates";

foreach ($letters as $i => $letter) {
	
	$letters[$i]['dates']['data'] = $con->get(array("excuse_letter_id"=>$letter['id']),["id","excuse_letter_date","wholeday"]);
	$letters[$i]['dates']['dels'] = [];	
	
	foreach ($letters[$i]['dates']['data'] as $key => $date) {
		
		if ($key == 0) $letters[$i]['letter_date_from'] = date("F j, Y",strtotime($date['excuse_letter_date']))." (".$date['wholeday'].")";
		if ($key == (count($letters[$i]['dates']['data'])-1)) $letters[$i]['letter_date_to'] = date("F j, Y",strtotime($date['excuse_letter_date']))." (".$date['wholeday'].")";
		
		$letters[$i]['dates']['data'][$key]['disabled'] = true;
		
	}
	
};

echo json_encode($letters);

?>