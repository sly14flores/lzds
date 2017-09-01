<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db2.php';

session_start();

$lzds = new pdo_db("lzds");
$lzdssystem = new pdo_db("lzdssystem");

$sql = "SELECT enrollee_id, enrollee_fid, enrollee_dob, enrollee_lname, enrollee_fname, enrollee_mname, enrollee_sy FROM enrollees";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " WHERE CONCAT(enrollee_lname, ' ', enrollee_fname, ' ', enrollee_mname) = '".$_POST['q']['fullname']."'";
$sql .= " ORDER BY enrollee_dob, enrollee_lname, enrollee_sy";

$students = $lzds->getData($sql);

foreach ($students as $key => $student) {
	
	$students[$key]["enrollee_no"] = $key;
	$students[$key]["enrollee_dob"] = date("M j, Y",strtotime($student["enrollee_dob"]));
	$students[$key]["added"] = false;
	$students[$key]["enrollee_imported"] = "No";
	$imported = $lzdssystem->getData("SELECT * FROM enrollments WHERE old_table_pk = ".$student['enrollee_id']);
	if (count($imported)) $students[$key]["enrollee_imported"] = "Yes";
	
}

echo json_encode($students);

?>