<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$tables = array("excuse_letters","excuse_letters_dates");

$dels = $_POST['dates']['dels'];
$excuse_letters_dates = $_POST['dates']['data'];
unset($_POST['dates']);
$excuse_letters = $_POST;

$con = new pdo_db($tables[0]);

if ($excuse_letters['id']) { # update
	
	$excuse_letters['update_log'] = "CURRENT_TIMESTAMP";
	$update = $con->updateObj($excuse_letters,'id');
	$excuse_letter_id = $excuse_letters['id'];
	
} else { # insert
	
	unset($excuse_letters['id']);
	$excuse_letters['system_log'] = "CURRENT_TIMESTAMP";
	$insert = $con->insertObj($excuse_letters);
	$excuse_letter_id = $con->insertId;
	
};

if (count($dels)) {
	
	$con->table = $tables[1];	
	$delete = $con->deleteData(array("id"=>implode(",",$dels)));	
	
};

if (count($excuse_letters_dates)) {
	
	$con->table = $tables[1];
	
	foreach ($excuse_letters_dates as $date) {
	
		unset($date['disabled']);
		$date['excuse_letter_date'] = date("Y-m-d",strtotime($date['excuse_letter_date']));
	
		if ($date['id']) { # update
			
			$date['update_log'] = "CURRENT_TIMESTAMP";
			$update = $con->updateData($date,'id');
			
		} else { # insert
			
			$date['excuse_letter_id'] = $excuse_letter_id;
			$date['system_log'] = "CURRENT_TIMESTAMP";
			$insert = $con->insertData($date);
			
		}
		
	};
	
};

?>