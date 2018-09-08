<?php

header("content-type: application/json; charset=utf-8");
header("access-control-allow-origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

$id = $_POST['enrollment_id'];

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.school_id, enrollments.enrollment_school_year, students.lastname, students.firstname, students.middlename, students.date_of_birth FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.id = $id";
$student = $con->getData($sql);

echo json_encode($student[0]);

?>