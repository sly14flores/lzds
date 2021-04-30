<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$student = $con->getData("SELECT * FROM students WHERE id = $_POST[id]");

if ($student[0]['date_of_birth'] == "0000-00-00") $staff[0]['date_of_birth'] = null;
if ($student[0]['date_of_birth'] != null) $staff[0]['date_of_birth'] = date("m/d/Y",strtotime($student[0]['date_of_birth']));
unset($student[0]['system_log']);
unset($student[0]['update_log']);

$parents_guardians = $con->getData("SELECT * FROM parents_guardians WHERE student_id = $_POST[id]");
foreach ($parents_guardians as $key => $pg) {
	unset($parents_guardians[$key]['system_log']);
	unset($parents_guardians[$key]['update_log']);	
}

$student[0]['gp4ps'] = ($student[0]['gp4ps'])?true:false;
$student[0]['gpips'] = ($student[0]['gpips'])?true:false;
$student[0]['ecd'] = ($student[0]['ecd'])?true:false;
$student[0]['pwd'] = ($student[0]['pwd'])?true:false;

$student[0]['enrollment_discount'] = array("id"=>$student[0]['enrollment_discount']);

echo json_encode(array("student"=>$student[0],"parents_guardians"=>$parents_guardians));

?>