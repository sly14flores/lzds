<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require('../db.php');
require('../classes.php');

$con = new pdo_db();

$school_year = (isset($_POST['school_year']))?$_POST['school_year']:current_sy($con);

$and_level = "";

if ((isset($_POST['level']))&&($_POST['level']['id']>0)) {
	
	$and_level = " AND enrollments.grade = ".$_POST['level']['id'];
	
};

$enrollments = $con->getData("SELECT id, student_id, (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, (SELECT students.lrn from students WHERE students.id = enrollments.student_id) lrn, (SELECT students.home_address FROM students WHERE students.id = enrollments.student_id) address, (SELECT IFNULL(students.email_address,'') FROM students WHERE students.id = enrollments.student_id) email_address, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, (SELECT school_years.school_year FROM school_years WHERE school_years.id = enrollments.enrollment_school_year) school_year, (SELECT students_discounts.amount FROM students_discounts WHERE students_discounts.enrollment_id = enrollments.id) discount, enrollments.enrollment_date, (SELECT SUM(students_fees.amount) FROM students_fees WHERE students_fees.enrollment_id = enrollments.id) sub_total FROM enrollments WHERE enrollment_school_year = ".$school_year['id']."$and_level");

$emails = [];

foreach ($enrollments as $enrollment) {

	$sub_total = $enrollment['sub_total'];

	$total = $sub_total-$enrollment['discount'];

	$payments = $con->getData("SELECT description, payment_month, official_receipt, amount, payment_date FROM payments WHERE enrollment_id = ".$enrollment['id']);

	$total_payment = 0;
	$balance = 0;
	foreach ($payments as $i => $_payment) {
		
		$total_payment += $_payment['amount'];
		
	};

	$balance = $total-$total_payment;

	$parent_guardian = parent_guardian($con,$enrollment['student_id']);;
	$address = $enrollment['address'];

	$current_sy = $enrollment['school_year'];

	$content = "";
	$content .= "Billing Office<br>";
	$content .= "Lord of Zion Divine School<br>";
	$content .= "Paratong, Bacnotan, La Union, 2515<br>";
	$content .= "Tel. No.: (072) 607 4004<br><br>";
	$content .= date("F j, Y") . "<br><br>";

	$content .= "$parent_guardian<br>";
	$content .= "Parent/Guardian<br>";
	$content .= "$address<br><br>";

	$content .= "Dear $parent_guardian,<br><br>";
	$content .= "This is a friendly reminder of your account with us.  Our records indicate that you have an outstanding balance of <span style=\"font-weight: bold; text-decoration: underline;\"><strong>Php. ".number_format($balance,2)."</strong></span>  as of this date: " . date("F j, Y") . " for school year " . $current_sy;

	$content .= "In order to keep your account in good standing, may we ask you to kindly make your payments up-to-date before your child's/children's scheduled quarterly examinations.<br><br>";

	$content .= "If the above amount has already been paid and sent, please disregard this notice and we apologize for any inconvenience. Otherwise, please make an appointment with us and settle the due amount in our office from Mondays to Fridays, 7:30am to 4:30pm.<br><br>";

	$content .= "Thank you for your cooperation regarding this matter.<br><br>";

	$content .= "Sincerely,<br><br>";
	$content .= "LZDS Billing<br><br>";

	$email = array("content"=>$content,"email_address"=>$enrollment['email_address']);

	$emails[] = $email;

};

echo json_encode($emails);

?>