<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once 'analyzer.php';

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.student_id, enrollments.rfid, CONCAT(students.firstname, ' ', students.middlename, ' ', students.lastname) fullname FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.enrollment_school_year = ".$_POST['sy']['id']." AND grade = ".$_POST['grade']['id']." AND section = ".$_POST['section']['id'];
$students = $con->getData($sql);

echo json_encode($students);

?>