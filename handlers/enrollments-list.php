<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$enrollments = $con->getData("SELECT enrollment_school_year, school_id, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT description FROM sections WHERE sections.id = section) section, DATE_FORMAT(enrollment_date,'%M %e, %Y') enrollment_date FROM enrollments WHERE student_id = $_POST[id]");

echo json_encode($enrollments);

?>