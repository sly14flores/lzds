<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("schedules");

if ($_POST['schedule']['id']) { // > 0 - update
	$_POST['schedule']['system_log'] = "CURRENT_TIMESTAMP";
	$schedule = $con->updateData($_POST['schedule'],'id');
	$schedule_id = $_POST['schedule']['id'];
} else { // 0 - insert
	unset($_POST['schedule']['id']);
	$_POST['schedule']['update_log'] = "CURRENT_TIMESTAMP";
	$schedule = $con->insertData($_POST['schedule']);
	$schedule_id = $con->insertId;
}

if (count($_POST['schedule_details'])) {
	
	$con->table = "schedules_details";
	
	foreach ($_POST['schedule_details'] as $key => $item) {
		
		unset($_POST['schedule_details'][$key]['disabled']);
		$_POST['schedule_details'][$key]['morning_in'] = date("H:i:s",strtotime($item['morning_in']));
		$_POST['schedule_details'][$key]['morning_cutoff'] = date("H:i:s",strtotime($item['morning_cutoff']));
		$_POST['schedule_details'][$key]['morning_out'] = date("H:i:s",strtotime($item['morning_out']));
		$_POST['schedule_details'][$key]['lunch_break_cutoff'] = date("H:i:s",strtotime($item['lunch_break_cutoff']));
		$_POST['schedule_details'][$key]['afternoon_in'] = date("H:i:s",strtotime($item['afternoon_in']));
		$_POST['schedule_details'][$key]['afternoon_cutoff'] = date("H:i:s",strtotime($item['afternoon_cutoff']));
		$_POST['schedule_details'][$key]['afternoon_out'] = date("H:i:s",strtotime($item['afternoon_out']));
		
		if ($item['id'] > 0) {
			$_POST['schedule_details'][$key]['update_log'] = "CURRENT_TIMESTAMP";			
			$schedule_detail = $con->updateData($_POST['schedule_details'][$key],'id');
		} else {
			$_POST['schedule_details'][$key]['system_log'] = "CURRENT_TIMESTAMP";
			unset($_POST['schedule_details'][$key]['id']);
			$_POST['schedule_details'][$key]['schedule_id'] = $schedule_id;
			$schedule_detail = $con->insertData($_POST['schedule_details'][$key]);			
		}
		
	}
	
}

?>