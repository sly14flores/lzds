<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$where = "";

if (isset($_POST['school_year'])) {
	
	if ($_POST['school_year']['id']>0) $where = " LEFT JOIN enrollments ON students.id = enrollments.student_id WHERE enrollments.enrollment_school_year = ".$_POST['school_year']['id'];
	
};

$students = $con->getData("SELECT students.id, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, (SELECT school_years.school_year FROM enrollments LEFT JOIN school_years ON enrollments.enrollment_school_year = school_years.id WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1) recent_sy, (SELECT grade_levels.description FROM enrollments LEFT JOIN grade_levels ON enrollments.grade = grade_levels.id WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1) recent_level FROM students$where");

echo json_encode($students);

?>