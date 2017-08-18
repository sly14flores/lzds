<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, (SELECT lrn FROM students WHERE students.id = enrollments.student_id) lrn, school_id, (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, (SELECT school_years.school_year FROM school_years WHERE school_years.id = enrollment_school_year) enrollment_school_year, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT description FROM sections WHERE sections.id = section) section, DATE_FORMAT(enrollment_date,'%M %e, %Y') enrollment_date, (SELECT SUM(students_fees.amount) FROM students_fees WHERE students_fees.enrollment_id = enrollments.id) sub_total, (SELECT amount FROM students_discounts WHERE students_discounts.enrollment_id = enrollments.id) discount, (SELECT SUM(amount) FROM payments WHERE payments.enrollment_id = enrollments.id) payments FROM enrollments WHERE id = ".$_POST['id'];
$enrollment_info = $con->getData($sql);
	
$enrollment_info[0]['total'] = $enrollment_info[0]['sub_total'] - $enrollment_info[0]['discount'];
$enrollment_info[0]['balance'] = $enrollment_info[0]['total'] - $enrollment_info[0]['payments'];

echo json_encode($enrollment_info[0]);

?>