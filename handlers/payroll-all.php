<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$period = $_POST['period'];
$month = $_POST['month']['month'];
$sy = $_POST['payroll_sy']['school_year'];

$years = explode("-",$sy);

$all = [];

$cdate = ($years[0]==date("Y"))?$years[0]."-".$month."-01":date("Y",strtotime("+1 Year",strtotime($years[0]."-".$month."-01")))."-".$month."-01";
$all['period'] = date("M 1-15, Y",strtotime($cdate));
if ($period == "second") $all['period'] = date("M 16-t, Y",strtotime($cdate));

$all['sy'] = $sy;
$all['date'] = date("F j, Y");

$rows = [];

$payroll = $con->getData("SELECT * FROM payroll WHERE payroll_month = '$month' AND payroll_period = '$period' AND payroll_sy = ".$_POST['payroll_sy']['id']);

foreach ($payroll as $key => $p) {
	
	$rows[$key]["id"] = "";
	$rows[$key]["lastname"] = "";
	$rows[$key]["firstname"] = "";
	$rows[$key]["mi"] = "";
	
	$rows[$key]["basic_pay"] = "";
	$rows[$key]["cola"] = "";
	$rows[$key]["gross_pay"] = "";
	$rows[$key]["sss_premium"] = "";
	$rows[$key]["hdmf_premium"] = "";
	$rows[$key]["phic_premium"] = "";
	$rows[$key]["tax"] = "";
	$rows[$key]["salary_loan"] = "";
	$rows[$key]["other_loans"] = "";
	$rows[$key]["deduction"] = "";
	$rows[$key]["incentive"] = "";
	$rows[$key]["net_pay"] = "";
	$rows[$key]["signature"] = "";
	
}

header("Content-type: application/json");
echo json_encode(array("info"=>$all,"row"=>$rows));

?>