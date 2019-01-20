<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$sy = $_POST['school_year']['id'];

$filters = [];
$filters[] = array("enrollments.enrollment_school_year = $sy");

switch ($_POST['coverage']) {
	
	case "Daily":

		$date = date("Y-m-d",strtotime($_POST['date']));
		$filters[] = array("payments.payment_date = '$date'");	

	break;
	
	case "Weekly":
	
		$from = date("Y-m-d",strtotime($_POST['week_from']));
		$to = date("Y-m-d",strtotime($_POST['week_to']));
		$filters[] = array("(payments.payment_date >= '$from' AND payments.payment_date <= '$to')");	
	
	break;	
	
	case "Monthly":
	
		$year = $_POST['year'];
		$month = $_POST['month']['id'];
		$filters[] = array("payments.payment_date LIKE '$year-$month-%'");
	
	break;	
	
	case "Annually":
	
		$year = $_POST['year'];
		$filters[] = array("payments.payment_date LIKE '$year-%'");		
	
	break;	
	
	case "SY":
	
	break;	
	
};

$where = "";

foreach ($filters as $i => $filter) {
	
	$criterion = $filter[0];
	if ($i==0) $where .= " $criterion";
	else $where .= " AND $criterion";
	
};

$sql = "SELECT payments.payment_date, (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, enrollments.school_id, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, (SELECT students.gender FROM students WHERE students.id = enrollments.student_id) gender, (SELECT students.home_address FROM students WHERE students.id = enrollments.student_id) home_address, payments.official_receipt, payments.amount FROM payments LEFT JOIN enrollments ON payments.enrollment_id = enrollments.id WHERE $where ORDER BY payments.payment_date ASC";

$collections = $con->getData($sql);

foreach ($collections as $i => $collection) {
	
	$collections[$i]['payment_date'] = date("M j, Y",strtotime($collection['payment_date']));
	$collections[$i]['amount'] = number_format($collection['amount'],2);
	
};

echo json_encode($collections);

?>