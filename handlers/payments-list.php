<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$school_year = (isset($_POST['school_year']))?$_POST['school_year']:current_sy($con);

$and_level = "";

if ((isset($_POST['level']))&&($_POST['level']['id']>0)) {
	
	$and_level = " AND enrollments.grade = ".$_POST['level']['id'];
	
};

$sql = "SELECT enrollments.id,";
$sql .= " (SELECT students.lrn FROM students WHERE students.id = enrollments.student_id) lrn,";
$sql .= " enrollments.school_id,";
$sql .= " enrollments.origin,";
$sql .= " enrollments.enrollee_rn,";
$sql .= " students.student_status,";
$sql .= " (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname,";
$sql .= " (SELECT school_years.school_year FROM school_years WHERE school_years.id = enrollments.enrollment_school_year) enrollment_school_year,";
$sql .= " (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade,";
$sql .= " (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section,";
$sql .= " DATE_FORMAT(enrollments.enrollment_date,'%M %e, %Y') enrollment_date,";
$sql .= " (SELECT SUM(students_fees.amount) FROM students_fees WHERE students_fees.enrollment_id = enrollments.id) sub_total,";
$sql .= " (SELECT students_discounts.amount FROM students_discounts WHERE students_discounts.enrollment_id = enrollments.id) discount,";
$sql .= " (SELECT SUM(payments.amount) FROM payments WHERE payments.enrollment_id = enrollments.id) payments,";
$sql .= " IFNULL((SELECT SUM(students_vouchers.amount) FROM students_vouchers WHERE students_vouchers.enrollment_id = enrollments.id),0) voucher";
$sql .= " FROM enrollments";
$sql .= " LEFT JOIN students ON enrollments.student_id = students.id";
$sql .= " WHERE enrollment_school_year = ".$school_year['id']."$and_level ORDER BY students.lastname, students.firstname, students.middlename";

$payments = $con->getData($sql);

foreach ($payments as $i => $payment) {
	
	$payments[$i]['payments'] = ($payment['payments']==null)?0:$payment['payments'];
	$payments[$i]['total'] = $payment['sub_total'] - $payment['discount'];
	$payments[$i]['balance'] = $payments[$i]['total'] - $payment['payments'];

	if($payment['origin']=='walk-in') $payments[$i]['origin']='Walk-in';
	if($payment['origin']=='online') $payments[$i]['origin']='Online';
	
}

echo json_encode($payments);

?>