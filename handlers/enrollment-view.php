<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$enrollment = $con->getData("SELECT id, school_id, enrollment_school_year, grade, section FROM enrollments WHERE id = $_POST[id]");
$enrollment_school_year = $con->getData("SELECT id, school_year FROM school_years WHERE id = ".$enrollment[0]['enrollment_school_year']);
$grade = $con->getData("SELECT id, description FROM grade_levels WHERE id = ".$enrollment[0]['grade']);
$sections = $con->getData("SELECT id, description FROM sections WHERE level_id = ".$enrollment[0]['grade']);
$section = $con->getData("SELECT id, description FROM sections WHERE id = ".$enrollment[0]['section']);

$enrollment[0]['enrollment_school_year'] = $enrollment_school_year[0];
$enrollment[0]['grade'] = $grade[0];
$enrollment[0]['grade']['sections'] = $sections;
$enrollment[0]['section'] = (count($section))?$section[0]:array("id"=>0,"description"=>"");

echo json_encode($enrollment[0]);

?>