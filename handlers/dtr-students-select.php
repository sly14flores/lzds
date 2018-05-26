<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$where = "";
$filters = [];

if (isset($_POST['sy'])) if ($_POST['sy']['id'] > 0) $filters[] = array("enrollments.enrollment_school_year"=>$_POST['sy']['id']);
if (isset($_POST['level'])) if ($_POST['level']['id'] > 0) $filters[] = array("enrollments.grade"=>$_POST['level']['id']);
if (isset($_POST['section'])) if ($_POST['section']['id'] > 0) $filters[] = array("enrollments.section"=>$_POST['section']['id']);

foreach ($filters as $i => $filter) {
	
	if ($i == 0) $where.="WHERE ".array_keys($filter)[0]." = ".$filter[array_keys($filter)[0]]." ";	
	else $where.="AND ".array_keys($filter)[0]." = ".$filter[array_keys($filter)[0]]." ";	
	
};

$sql = "SELECT enrollments.id, enrollments.student_id, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, enrollments.rfid, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, IFNULL((SELECT sections.description FROM sections WHERE sections.id = enrollments.section),'') section FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id $where";

$students = $con->getData($sql);

echo json_encode($students);

?>