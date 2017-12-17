<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT enrollments.id, enrollments.student_id, enrollments.school_id, CONCAT(students.firstname, ' ', SUBSTRING(students.middlename, 1, 1), '. ', students.lastname) fullname, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, enrollments.rfid FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollment_school_year = 5";

$students = $con->getData($sql);

echo json_encode($students);

?>