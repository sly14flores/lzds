<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, lrn, CONCAT(lastname, ', ', firstname, ' ', middlename) fullname, contact_no, (SELECT grade FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollment_school_year DESC LIMIT 1) current_level, (SELECT enrollment_school_year FROM enrollments WHERE enrollments.student_id = students.id ORDER BY enrollment_school_year DESC LIMIT 1) current_sy FROM students";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " WHERE id = ".$_POST['q']['id'];

$students = $con->getData($sql);

echo json_encode($students);

?>