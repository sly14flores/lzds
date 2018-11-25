<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.rfid, enrollments.school_id, CONCAT(students.lastname, ', ', students.firstname, students.middlename) fullname, students.gender FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.grade = ".$_POST['grade']['id']." AND enrollments.section = ".$_POST['section']['id']." AND enrollments.enrollment_school_year = ".$_POST['sy']['id']." ORDER BY students.gender, students.lastname, students.firstname, students.middlename ASC";
$students = $con->getData($sql);

$from = $_POST['year']."-".$_POST['month']['month']."-01";
$to = $_POST['year']."-".$_POST['month']['month']."-15";

$headers = [];
$headers[] = array("title"=>"No", "dataKey"=>"no");
// $headers[] = array("title"=>"ID", "dataKey"=>"school_id");
$headers[] = array("title"=>"Name", "dataKey"=>"fullname");
$headers[] = array("title"=>"Gender", "dataKey"=>"gender");

$columnStyles = [];
$columnStyles['fullname'] = array("columnWidth"=>100);

$day = $from;
while (strtotime($day) <= strtotime($to)) {

	$headers[] = array("title"=>date("d",strtotime($day))."-".date("D",strtotime($day)), "dataKey"=>date("d",strtotime($day)));

	$columnStyles[date("d",strtotime($day))] = array("columnWidth"=>45);	
	
	$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
	
};

$_students = $students;
foreach ($_students as $i => $student) {
	
	$day = $from;
	while (strtotime($day) <= strtotime($to)) {
	
		$_students[$i]['no'] = $i+1;
		
		$logs = $con->getData("SELECT time_log FROM attendances WHERE rfid = '".$student['rfid']."' AND time_log LIKE '$day%' ORDER BY time_log");
		
		$_logs = [];
		foreach ($logs as $log) {
			
			$_logs[] = date("h:i A",strtotime($log['time_log']));
			
		};
		
		$dtr = implode(" ",$_logs);
		
		$_students[$i][date("d",strtotime($day))] = $dtr;
	
		$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
	
	};
	
};

$first = array("headers"=>$headers,"columnStyles"=>$columnStyles,"students"=>$_students);

$from = $_POST['year']."-".$_POST['month']['month']."-16";
$to = date("Y-m-t",strtotime($_POST['year']."-".$_POST['month']['month']."-01"));

$headers = [];
$headers[] = array("title"=>"No", "dataKey"=>"no");
// $headers[] = array("title"=>"ID", "dataKey"=>"school_id");
$headers[] = array("title"=>"Name", "dataKey"=>"fullname");
$headers[] = array("title"=>"Gender", "dataKey"=>"gender");

$columnStyles = [];
$columnStyles['fullname'] = array("columnWidth"=>100);

$day = $from;
while (strtotime($day) <= strtotime($to)) {

	$headers[] = array("title"=>date("d",strtotime($day))."-".date("D",strtotime($day)), "dataKey"=>date("d",strtotime($day)));

	$columnStyles[date("d",strtotime($day))] = array("columnWidth"=>45);
	
	$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
	
};

$_students = $students;
foreach ($_students as $i => $student) {
	
	$day = $from;
	while (strtotime($day) <= strtotime($to)) {
	
		$_students[$i]['no'] = $i+1;
		
		$logs = $con->getData("SELECT time_log FROM attendances WHERE rfid = '".$student['rfid']."' AND time_log LIKE '$day%' ORDER BY time_log");		
		
		$_logs = [];
		foreach ($logs as $log) {
			
			$_logs[] = date("h:i A",strtotime($log['time_log']));
			
		};
		
		$dtr = implode(" ",$_logs);		
	
		$_students[$i][date("d",strtotime($day))] = $dtr;
	
		$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
	
	};
	
};

$second = array("headers"=>$headers,"columnStyles"=>$columnStyles,"students"=>$_students);

$data = array("first"=>$first,"second"=>$second);

echo json_encode($data);

?>