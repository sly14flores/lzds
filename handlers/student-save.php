<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("students");

/*
** filters: date
*/

if (isset($_POST['date_of_birth'])) $_POST['date_of_birth'] = date("Y-m-d",strtotime($_POST['date_of_birth']));
	
/*
**
*/

if ($_POST['student']['id']) { // > 0 - update
	$_POST['update_log'] = "CURRENT_TIMESTAMP";
	$student = $con->updateData($_POST['student'],'id');
	$student_id = $_POST['student']['id'];
} else { // 0 - insert
	unset($_POST['id']);
	$student = $con->insertData($_POST['student']);
	$student_id = $con->insertId;	
}

if (count($_POST['parents_guardians'])) {
	$con->table = "parents_guardians";
	foreach ($_POST['parents_guardians'] as $key => $pg) {
		if ($pg['id']) {
			$_POST['parents_guardians'][$key]['update_log'] = "CURRENT_TIMESTAMP";			
			$student_pg = $con->updateData($_POST['parents_guardians'][$key],'id');
		} else { echo "Lorem";
			unset($_POST['parents_guardians'][$key]['id']);			
			$_POST['parents_guardians'][$key]['student_id'] = $student_id;
			$student_pg = $con->insertData($_POST['parents_guardians'][$key]);
		}
	}
}

if (count($_POST['parents_guardians_dels'])) {
	$con->table = "parents_guardians";	
	$delete = $con->deleteData(array("id"=>implode(",",$_POST['parents_guardians_dels'])));
}

?>