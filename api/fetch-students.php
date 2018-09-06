<?php

header("content-type: application/json; charset=utf-8");
header("access-control-allow-origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.school_id, enrollments.enrollment_school_year, students.lastname, students.firstname, students.middlename, students.date_of_birth FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollment_school_year = ".$_POST['id']." ORDER BY enrollments.school_id";
$students = $con->getData($sql);

echo json_encode($students);

?>