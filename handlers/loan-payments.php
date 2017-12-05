<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$info = [];
$total = 0;
$payments = $con->getData("SELECT id, DATE_FORMAT(system_log,'%M %e, %Y') payment_date, amount payment_amount, payroll_id FROM loans_payments WHERE loan_id = $_POST[id]");
foreach ($payments as $key => $p) {
	$payroll = $con->getData("SELECT * FROM payroll WHERE id = ".$p['payroll_id']);
	$payments[$key]['remarks'] = "Payroll Period: ";
	if ($payroll[0]['payroll_period'] == "second") $payments[$key]['remarks'] .= date("M 16-t, Y",strtotime($payroll[0]['payroll_date']));	
	else $payments[$key]['remarks'] .= date("M 1-15, Y",strtotime($payroll[0]['payroll_date']));
	$total += $p['payment_amount'];
}
$info['total'] = $total;

header("Content-type: application/json");
echo json_encode(array("info"=>$info,"rows"=>$payments));

?>