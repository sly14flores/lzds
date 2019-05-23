<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$leaves = $con->getData("SELECT id, DATE_FORMAT(leave_date,'%M %e, %Y') leave_date, leave_description, leave_wholeday, with_pay FROM leaves WHERE staff_id = $_POST[id]");

foreach ($leaves as $i => $leave) {
	
	$leaves[$i]['with_pay'] = ($leave['with_pay'])?"true":"false";
	
};

header("Content-type: application/json");
echo json_encode($leaves);

?>