<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.school_id, CONCAT(students.lastname, ', ', students.firstname, students.middlename) fullname, students.gender FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.grade = ".$_POST['grade']['id']." AND enrollments.section = ".$_POST['section']['id']." AND enrollments.enrollment_school_year = ".$_POST['sy']['id']." ORDER BY students.gender, students.lastname, students.firstname, students.middlename ASC";
$students = $con->getData($sql);

$data = array("students"=>$students);

echo json_encode($data);

?>