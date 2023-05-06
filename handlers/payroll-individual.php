<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

$con = new pdo_db("payroll");

$staff = $con->getData("SELECT * FROM staffs WHERE id = $_POST[id]");

$payroll = array(
	"staff_id"=>$_POST['id'],
	"payroll_period"=>$_POST['period'],
	"payroll_month"=>$_POST['month']['month'],
	"payroll_sy"=>$_POST['payroll_sy']['id'],
	"payroll_date"=>"CURRENT_TIMESTAMP",
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayroll = $con->getData("SELECT * FROM payroll WHERE staff_id = ".$_POST['id']." AND payroll_period = '".$_POST['period']."' AND payroll_month = ".$_POST['month']['month']." AND payroll_sy = ".$_POST['payroll_sy']['id']);

if ($_POST['option']) {
	if (count($hasPayroll)) {
		$delete = $con->deleteData(array("id"=>implode(",",array($hasPayroll[0]['id']))));
		$hasPayroll = [];
	}
};

if (count($hasPayroll) == 0) {
	$con->insertData($payroll);
	$payroll_id = $con->insertId;
	$hasPayroll = $con->getData("SELECT * FROM payroll WHERE staff_id = ".$_POST['id']." AND payroll_period = '".$_POST['period']."' AND payroll_month = ".$_POST['month']['month']." AND payroll_sy = ".$_POST['payroll_sy']['id']);	
} else {
	$payroll_id = $hasPayroll[0]['id'];
}

$hasPayroll[0]['basic_pay'] = $staff[0]['basic_pay'];

# Payroll Pays
$payroll_pays = [];

# Basic Pay
$payroll_pays[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Basic Pay",
	"description_field"=>"basic_pay",
	"amount"=>$staff[0]['basic_pay'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Cola
$payroll_pays[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"COLA",
	"description_field"=>"cola",
	"amount"=>$staff[0]['cola'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Sub Allowances
$payroll_pays[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Sub Allowances",
	"description_field"=>"sub_allowances",
	"amount"=>0,
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Incentives
$payroll_pays[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Incentives",
	"description_field"=>"incentives",
	"amount"=>0,
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Bonus
$payroll_pays[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Bonus",
	"description_field"=>"bonus",
	"amount"=>0,
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayrollPays = $con->getData("SELECT * FROM payroll_pays WHERE payroll_id = $payroll_id");

if (count($hasPayrollPays) == 0) {
	$con->table = "payroll_pays";
	$con->insertDataMulti($payroll_pays);
} else {
	$payroll_pays = $con->getData("SELECT * FROM payroll_pays WHERE payroll_id = $payroll_id");
}

# Payroll Deductions
$payroll_deductions = [];

# SSS
$sss_period = "sss_amount_".$_POST['period'];
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"SSS",
	"description_field"=>$sss_period,
	"unit"=>"",	
	"amount"=>$staff[0][$sss_period],	
	"system_log"=>"CURRENT_TIMESTAMP"
);

# HDMF
$hdmf_period = "hdmf_amount_".$_POST['period'];
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"HDMF",
	"description_field"=>$hdmf_period,
	"unit"=>"",	
	"amount"=>$staff[0][$hdmf_period],	
	"system_log"=>"CURRENT_TIMESTAMP"
);

# PHIC
$phic_period = "phic_amount_".$_POST['period'];
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"PHIC",
	"description_field"=>$phic_period,
	"unit"=>"",	
	"amount"=>$staff[0][$phic_period],	
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Tax
$tax_period = "tax_amount_".$_POST['period'];
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Tax",
	"description_field"=>$tax_period,
	"unit"=>"",	
	"amount"=>$staff[0][$tax_period],	
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Loans
$first_day = date("Y-").$_POST['month']['month']."-01";
$middle_day = date("Y-").$_POST['month']['month']."-15";
$first_day_second = date("Y-m-16",strtotime($first_day));
$last_day = ($_POST['period']=="first")?date("Y-m-15",strtotime($first_day)):date("Y-m-t",strtotime($first_day));
$period_date = ($_POST['period']=="first")?$middle_day:$last_day;
if ($_POST['period']=="second") $first_day = $first_day_second;

$loans = $con->getData("SELECT id, staff_id, loan_type, loan_description, loan_date, loan_amount, IFNULL(loan_offset,0) loan_offset, loan_months, loan_monthly, loan_monthly_first, loan_monthly_second, loan_effectivity FROM loans WHERE staff_id = ".$_POST['id']." AND loan_paid = 0 AND '$period_date' >= loan_effectivity");

$loan_period = "loan_monthly_".$_POST['period'];
foreach ($loans as $key => $loan) {

	$loan_payments = $con->getData("SELECT IFNULL(SUM(loans_payments.amount),0) loan_payments FROM loans_payments WHERE loan_id = ".$loan['id']);
	$loan_balance = $loan['loan_amount']-$loan['loan_offset']-$loan_payments[0]['loan_payments']-$loan[$loan_period]; # add current loan payment

	if ($loan_balance <= 0) continue;

	$payroll_deductions[] = array(
		"payroll_id"=>$payroll_id,
		"description"=>$loan['loan_description'],
		"description_field"=>$loan['loan_type'],
		"unit"=>"",		
		"amount"=>$loan[$loan_period],
		"system_log"=>"CURRENT_TIMESTAMP"
	);

	$loan_payment = array(
		"loan_id"=>$loan['id'],
		"payroll_id"=>$payroll_id,
		"amount"=>$loan[$loan_period],
		"system_log"=>"CURRENT_TIMESTAMP"
	);

	$hasLoan = $con->getData("SELECT * FROM loans_payments WHERE payroll_id = $payroll_id AND loan_id = ".$loan['id']);
	
	if (count($hasLoan) == 0) {
		$con->table = "loans_payments";
		$con->insertData($loan_payment);
	};

};

# Tardiness
$tardiness = tardiness($con,$staff[0],$first_day,$last_day);
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Tardiness",
	"description_field"=>"tardiness",
	"unit"=>$tardiness['tardiness'],
	"amount"=>round($tardiness['amount']),
	"system_log"=>"CURRENT_TIMESTAMP"
);

# AWOL
$absences = absences($con,$staff[0],$first_day,$last_day);
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Leave",
	"description_field"=>"absences",
	"unit"=>$absences['absences'],
	"amount"=>$absences['amount'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayrollDeductions = $con->getData("SELECT * FROM payroll_deductions WHERE payroll_id = $payroll_id");

if (count($hasPayrollDeductions) == 0) {
	$con->table = "payroll_deductions";
	$con->insertDataMulti($payroll_deductions);
} else {
	$payroll_deductions = $con->getData("SELECT * FROM payroll_deductions WHERE payroll_id = $payroll_id");		
}


# Payroll Bonuses
$payroll_bonuses = [];

# 13th Month
$payroll_bonuses[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"13th Month",
	"description_field"=>"thirteenth_month",
	"amount"=>0,
	"system_log"=>"CURRENT_TIMESTAMP"
);

# Christmas
$payroll_bonuses[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Christmas Bonus",
	"description_field"=>"christmas_bonus",
	"amount"=>0,
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayrollBonuses = $con->getData("SELECT * FROM payroll_bonuses WHERE payroll_id = $payroll_id");

if (count($hasPayrollBonuses) == 0) {
	$con->table = "payroll_bonuses";
	$con->insertDataMulti($payroll_bonuses);
} else {
	$payroll_bonuses = $con->getData("SELECT * FROM payroll_bonuses WHERE payroll_id = $payroll_id");
}

$response = $hasPayroll[0];
$response['payroll_pays'] = $payroll_pays;
$response['payroll_deductions'] = $payroll_deductions;
$response['payroll_bonuses'] = $payroll_bonuses;

header("Content-type: application/json");
echo json_encode($response);

?>