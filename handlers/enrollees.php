<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$where = "";

$c = 1;
foreach ($_POST as $i => $filter) {	
	
	if ($i == "school_year") {
		if ($c == 1) $where .= " WHERE enrollment_school_year = ".$filter['id'];
		else $where .= " AND enrollment_school_year = ".$filter['id'];
	};

	if ($i == "origin") {
		if (isset($_POST['origin'])) {
			if ($c == 1) $where .= " WHERE enrollments.origin = '".$_POST['origin']."'";
			else $where .= " AND enrollments.origin = '".$_POST['origin']."'";
		}
	};
	
	if ($i == "level") {
		if ($filter['id'] > 0) {
			if ($c == 1) $where .= " WHERE grade = ".$filter['id'];
			else $where .= " AND grade = ".$filter['id'];
		};
	};
	
	if ($i == "section") {
		if ($filter['id'] > 0) {		
			if ($c == 1) $where .= " WHERE section = ".$filter['id'];
			else $where .= " AND section = ".$filter['id'];
		};
	};	
	
	++$c;
	
};

$sql = "SELECT enrollments.id, enrollments.enrollee_rn, students.lrn, enrollments.origin, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, students.gender, enrollments.school_id, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, enrollments.rfid, DATE_FORMAT(enrollments.enrollment_date,'%M %e, %Y') enrollment_date FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id".$where." ORDER BY students.lastname, students.firstname, students.middlename, students.gender";

$enrollees = $con->getData($sql);

foreach ($enrollees as $i => $e) {
	
	if($e['origin']=='walk-in') $enrollees[$i]['origin']='Walk-in';
	if($e['origin']=='online') $enrollees[$i]['origin']='Online';
	
};

echo json_encode($enrollees);

?>