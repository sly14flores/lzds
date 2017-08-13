<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$school_year = (isset($_POST['school_year']))?$_POST['school_year']:current_sy($con);

$sql = "SELECT id, school_id, (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, (SELECT school_years.school_year FROM school_years WHERE school_years.id = enrollment_school_year) enrollment_school_year, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT description FROM sections WHERE sections.id = section) section, DATE_FORMAT(enrollment_date,'%M %e, %Y') enrollment_date FROM enrollments WHERE enrollment_school_year = ".$school_year['id'];

$payments = $con->getData($sql);

echo json_encode($payments);

?>