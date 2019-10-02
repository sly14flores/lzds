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
	
	if ($i == "level") {
		if ($filter['id'] > 0) {
			if ($c == 1) $where .= " WHERE grade = ".$filter['id'];
			else $where .= " AND grade = ".$filter['id'];
		};
	};

	++$c;

};

$sql = "SELECT enrollments.id, students.lrn, enrollments.school_id, CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) fullname, students.gender, students.date_of_birth birthdate, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, students.home_address, students.contact_no FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id".$where." ORDER BY students.lastname, students.firstname, students.middlename";

$enrollees = $con->getData($sql);

foreach ($enrollees as $i => $enrollee) {
	
	$enrollees[$i]['birthdate'] = date("M j, Y",strtotime($enrollee['birthdate']));
	
	$remarks = (($enrollee['school_id']=="")||($enrollee['school_id']==null))?"":"Ok";
	
	// parent / guardian
	$sql = "SELECT id, student_id, relationship, CONCAT(first_name, ' ', SUBSTRING(middle_name,1,1), '. ', last_name, IF(ext_name=NULL,'', IF(ext_name='-','',CONCAT(' ',ext_name)))) fullname FROM parents_guardians WHERE relationship IN ('Father','Mother','Guardian') ORDER BY student_id";
	$parents_guardians = $con->getData($sql);
	
	$enrollees[$i]['remarks'] = $remarks;
	
};

echo json_encode($enrollees);

?>