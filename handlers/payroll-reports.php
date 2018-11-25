<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$report = $_POST['report'];
$period = $_POST['period'];
$month = $_POST['month']['month'];
$sy = $_POST['payroll_sy']['school_year'];

$years = explode("-",$sy);

$info = [];

$cdate = ($years[0]==date("Y"))?$years[0]."-".$month."-01":date("Y",strtotime("+1 Year",strtotime($years[0]."-".$month."-01")))."-".$month."-01";
$info['period'] = date("M 1-15, Y",strtotime($cdate));
if ($period == "second") $info['period'] = date("M 16-t, Y",strtotime($cdate));

$info['sy'] = $sy;
$info['date'] = date("F j, Y");

$rows = [];

// $payroll = $con->getData("SELECT * FROM payroll WHERE payroll_month = '$month' AND payroll_period = '$period' AND payroll_sy = ".$_POST['payroll_sy']['id']);
$sql = "SELECT payroll.id, payroll.staff_id, payroll.payroll_month, payroll.payroll_period, payroll.payroll_date, payroll.payroll_sy FROM payroll LEFT JOIN staffs ON payroll.staff_id = staffs.id WHERE payroll_month = '$month' AND payroll_period = '$period' AND payroll_sy = ".$_POST['payroll_sy']['id']." ORDER BY lastname, firstname, middlename ASC";
$payroll = $con->getData($sql);

foreach ($payroll as $key => $p) {
	
	$staff = $con->getData("SELECT school_id, lastname, firstname, SUBSTRING(middlename,1,1) mi FROM staffs WHERE id = ".$p['staff_id']);
	
	$rows[$key]["id"] = $staff[0]['school_id'];
	$rows[$key]["lastname"] = $staff[0]['lastname'];
	$rows[$key]["firstname"] = $staff[0]['firstname'];
	$rows[$key]["mi"] = $staff[0]['mi'];
	$rows[$key]["tardiness"] = "00:00:00";
	$rows[$key]["awol"] = "0";
	
	$deductions = $con->getData("SELECT * FROM payroll_deductions WHERE payroll_id = ".$p['id']);		

	foreach ($deductions as $deduction) {

		if ($deduction['description_field'] == "sss_amount_".$period) continue;
		if ($deduction['description_field'] == "hdmf_amount_".$period) continue;
		if ($deduction['description_field'] == "phic_amount_".$period) continue;
		if ($deduction['description_field'] == "tax_amount_".$period) continue;
		if ($deduction['description_field'] == "Salary") continue;
		if ($deduction['description_field'] == "Others") continue;

		if ($deduction['description_field'] == "tardiness") $rows[$key]["tardiness"] = $deduction['unit'];
		if ($deduction['description_field'] == "absences") $rows[$key]["awol"] = $deduction['unit'];

	};

}

// $totals["tardiness"] = 0;
// $totals["awol"] = 0;

// foreach ($rows as $key => $r) {
	
	// $totals["tardiness"] += $r['tardiness'];
	// $totals["awol"] += $r['awol'];
	
// }

// foreach ($totals as $i => $t) {
	
	// $totals[$i] = number_format(round($t,2),2);
	
// }

header("Content-type: application/json");
// echo json_encode(array("info"=>$info,"row"=>$rows,"total"=>$totals));
echo json_encode(array("info"=>$info,"row"=>$rows));

?>