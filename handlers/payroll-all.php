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

// $payroll = $con->getData("SELECT * FROM payroll WHERE payroll_month = '$month' AND payroll_period = '$period' AND payroll_sy = ".$_POST['payroll_sy']['id']);
// $sql = "SELECT payroll.id, payroll.staff_id, payroll.payroll_month, payroll.payroll_period, payroll.payroll_date, payroll.payroll_sy FROM payroll LEFT JOIN staffs ON payroll.staff_id = staffs.id WHERE payroll_month = '$month' AND payroll_period = '$period' AND payroll_sy = ".$_POST['payroll_sy']['id']." ORDER BY lastname, firstname, middlename ASC";
$sql = "SELECT payroll.id, payroll.staff_id, payroll.payroll_month, payroll.payroll_period, payroll.payroll_date, payroll.payroll_sy FROM payroll LEFT JOIN staffs ON payroll.staff_id = staffs.id WHERE payroll.payroll_month = '$month' AND payroll.payroll_period = '$period' AND payroll.payroll_sy = ".$_POST['payroll_sy']['id']." AND (staffs.employment_status = 'Regular' OR staffs.employment_status = 'Contractual') ORDER BY lastname, firstname, middlename ASC";
$payroll = $con->getData($sql);

foreach ($payroll as $key => $p) {
	
	$staff = $con->getData("SELECT school_id, lastname, firstname, SUBSTRING(middlename,1,1) mi FROM staffs WHERE id = ".$p['staff_id']);
	
	$rows[$key]["id"] = $staff[0]['school_id'];
	$rows[$key]["lastname"] = $staff[0]['lastname'];
	$rows[$key]["firstname"] = $staff[0]['firstname'];
	$rows[$key]["mi"] = $staff[0]['mi'];
	
	$pays = $con->getData("SELECT * FROM payroll_pays WHERE payroll_id = ".$p['id']);
	
	$gross_pay = 0;
	foreach ($pays as $pay) {
		if ($pay['description_field'] == "basic_pay") {
			$rows[$key]["basic_pay"] = $pay['amount']; 
		}
		if ($pay['description_field'] == "sub_allowances") $rows[$key]["cola"] = $pay['amount'];
		if ($pay['description_field'] == "incentives") $rows[$key]["incentive"] = $pay['amount'];	
		$gross_pay += $pay['amount'];
	}
	
	$rows[$key]["gross_pay"] = $gross_pay;
	
	$bonuses = 0;
	$payroll_bonuses = $con->getData("SELECT * FROM payroll_bonuses WHERE payroll_id = ".$p['id']);
	foreach ($payroll_bonuses as $b) {
		$bonuses += $b['amount'];
	}
	
	$deductions = $con->getData("SELECT * FROM payroll_deductions WHERE payroll_id = ".$p['id']);		
	
	$rows[$key]["tardiness_awol"] = 0;
	
	$rows[$key]["salary_loan"] = 0;
	$rows[$key]["other_loans"] = 0;
	$deduction_total = 0;
	foreach ($deductions as $deduction) {
		if ($deduction['description_field'] == "sss_amount_".$period) $rows[$key]["sss_premium"] = $deduction['amount'];
		if ($deduction['description_field'] == "hdmf_amount_".$period) $rows[$key]["hdmf_premium"] = $deduction['amount'];
		if ($deduction['description_field'] == "phic_amount_".$period) $rows[$key]["phic_premium"] = $deduction['amount'];
		if ($deduction['description_field'] == "tax_amount_".$period) $rows[$key]["tax"] = $deduction['amount'];
		if ($deduction['description_field'] == "Salary") $rows[$key]["salary_loan"] += $deduction['amount']; # loan
		if ($deduction['description_field'] == "Others") $rows[$key]["other_loans"] += $deduction['amount']; # other loan
		if ($deduction['description_field'] == "tardiness") $rows[$key]["tardiness_awol"] += $deduction['amount'];;
		if ($deduction['description_field'] == "absences") $rows[$key]["tardiness_awol"] += $deduction['amount'];;
		$deduction_total += $deduction['amount'];	
	}
	
	$rows[$key]["deduction"] = $deduction_total;

	$rows[$key]["net_pay"] = ($gross_pay/2)+$bonuses-$deduction_total;
	
	$rows[$key]["signature"] = "";
	
}

$totals["basic_pay"] = 0;
$totals["cola"] = 0;
$totals["gross_pay"] = 0;
$totals["sss"] = 0;
$totals["hdmf"] = 0;
$totals["phic"] = 0;
$totals["tax"] = 0;
$totals["tardiness_awol"] = 0;
$totals["salary_loan"] = 0;
$totals["other_loans"] = 0;
$totals["total_deductions"] = 0;
$totals["incentive"] = 0;
$totals["net_pay"] = 0;

foreach ($rows as $key => $r) {
	
	$totals["basic_pay"] += $r['basic_pay'];
	$totals["cola"] += $r['cola'];
	$totals["gross_pay"] += $r['gross_pay'];
	$totals["sss"] += $r['sss_premium'];
	$totals["hdmf"] += $r['hdmf_premium'];
	$totals["phic"] += $r['phic_premium'];
	$totals["tax"] += $r['tax'];
	$totals["tardiness_awol"] += $r['tardiness_awol'];
	$totals["salary_loan"] += $r['salary_loan'];
	$totals["other_loans"] += $r['other_loans'];
	$totals["total_deductions"] += $r['deduction'];
	$totals["incentive"] += $r['incentive'];
	$totals["net_pay"] += $r['net_pay'];
	
	$rows[$key]['basic_pay'] = number_format($r['basic_pay'],2);
	$rows[$key]['cola'] = number_format($r['cola'],2);
	$rows[$key]['gross_pay'] = number_format($r['gross_pay'],2);
	$rows[$key]['sss'] = number_format($r['sss_premium'],2);
	$rows[$key]['hdmf'] = number_format($r['hdmf_premium'],2);
	$rows[$key]['phic'] = number_format($r['phic_premium'],2);
	$rows[$key]['tax'] = number_format($r['tax'],2);
	$rows[$key]['tardiness_awol'] = number_format($r['tardiness_awol'],2);
	$rows[$key]['salary_loan'] = number_format($r['salary_loan'],2);
	$rows[$key]['other_loans'] = number_format($r['other_loans'],2);
	$rows[$key]['total_deductions'] = number_format($r['deduction'],2);
	$rows[$key]['incentive'] = number_format($r['incentive'],2);
	$rows[$key]['net_pay'] = number_format($r['net_pay'],2);
	
}

foreach ($totals as $i => $t) {
	
	$totals[$i] = number_format(round($t,2),2);
	
}

header("Content-type: application/json");
echo json_encode(array("info"=>$all,"row"=>$rows,"total"=>$totals));

?>