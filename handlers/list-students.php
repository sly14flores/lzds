<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT students.id, students.lrn, students.origin, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, DATE_FORMAT(students.date_of_birth,'%M %e, %Y') date_of_birth, students.contact_no, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = (SELECT enrollments.grade FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1)) current_level, (SELECT school_years.school_year FROM school_years WHERE school_years.id = (SELECT enrollments.enrollment_school_year FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1)) current_sy FROM students";
$where = "";

if (isset($_POST['origin'])) {

	$where .= " WHERE students.origin = '".$_POST['origin']."'";
	
}

$sql.=$where;

$sql.=" ORDER BY lastname, firstname, middlename";

$students = $con->getData($sql);


foreach ($students as $i => $student) {
	
	if($student['origin']=='walk-in') $students[$i]['origin']='Walk-in';
	if($student['origin']=='online') $students[$i]['origin']='Online';
	
	// $students[$i]['fullname'] = utf8_decode($student['fullname']);
	// $students[$i]['fullname'] = iconv('UTF-8', 'ISO-8859-1', $student['fullname']);
	
};

echo json_encode($students);

?>