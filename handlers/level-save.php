<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("grade_levels");

$sections = $_POST['sections'];
$dels = $_POST['dels'];

unset($_POST['sections']);
unset($_POST['dels']);

if ($_POST['id']) {

	$_POST['update_log'] = "CURRENT_TIMESTAMP";	
	$level = $con->updateData($_POST,'id');
	$level_id = $_POST['id'];
	
} else {

	unset($_POST['id']);
	$_POST['system_log'] = "CURRENT_TIMESTAMP";
	$level = $con->insertData($_POST);
	$level_id = $con->insertId;	

}

if (count($dels)) {
	
	$con->table = "sections";	
	$delete = $con->deleteData(array("id"=>implode(",",$dels)));	

}

if (count($sections)) {
	
	$con->table = "sections";
	
	foreach ($sections as $key => $section) {		
		
		unset($sections[$key]['disabled']);
		
		if ($section['id']) {
			
			$sections[$key]['teacher'] = $sections[$key]['teacher']['id'];
			$sections[$key]['update_log'] = "CURRENT_TIMESTAMP";
			$insert = $con->updateData($sections[$key],'id');			
			
		} else {
			
			unset($sections[$key]['id']);
			$sections[$key]['teacher'] = $sections[$key]['teacher']['id'];			
			$sections[$key]['system_log'] = "CURRENT_TIMESTAMP";
			$sections[$key]['level_id'] = $level_id;
			$insert = $con->insertData($sections[$key]);			
			
		}
		
	}
	
}

?>