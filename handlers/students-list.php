<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT students.id, students.lrn, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, DATE_FORMAT(students.date_of_birth,'%M %e, %Y') date_of_birth, students.contact_no, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = (SELECT enrollments.grade FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1)) current_level, (SELECT school_years.school_year FROM school_years WHERE school_years.id = (SELECT enrollments.enrollment_school_year FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollments.enrollment_school_year DESC LIMIT 1)) current_sy FROM students ORDER BY lastname, firstname, middlename";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " WHERE id = ".$_POST['q']['id'];

$students = $con->getData($sql);

echo json_encode($students);

?>