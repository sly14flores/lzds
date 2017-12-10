<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.student_id, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, enrollments.rfid, enrollments.schedule_id FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollment_school_year = ".$_POST['sy'];

$students = $con->getData($sql);

echo json_encode($students);

?>