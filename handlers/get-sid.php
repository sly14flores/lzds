<?php

header("content-type: application/json; charset=utf-8");
header("access-control-allow-origin: *");

require_once '../db.php';
require_once '../classes.php';

$con = new pdo_db();

$school_year = current_sy($con);

// $sql = "SELECT enrollee_id, enrollee_fid, enrollee_lname, enrollee_fname, enrollee_mname, enrollee_grade, (SELECT section_name FROM sections WHERE section_id = enrollee_section) student_section, enrollee_contact FROM enrollees WHERE enrollee_sy = '2017' AND enrollee_fid LIKE '16%'";
$sql = "SELECT enrollments.id enrollee_id, enrollments.school_id enrollee_fid, students.lastname enrollee_lname, students.firstname enrollee_fname, students.middlename enrollee_mname, students.gender, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) enrollee_grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) student_section, students.contact_no enrollee_contact FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.enrollment_school_year = ".$school_year['id'];

$students = $con->getData($sql);

echo json_encode($students);

?>