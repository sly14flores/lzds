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
	"payroll_date"=>"CURRENT_TIMESTAMP",
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayroll = $con->getData("SELECT * FROM payroll WHERE staff_id = ".$_POST['id']." AND payroll_period = '".$_POST['period']."' AND payroll_month = ".$_POST['month']['month']);

if (count($hasPayroll) == 0) {
	$con->insertData($payroll);
	$payroll_id = $con->insertId;	
} else {
	$payroll_id = $hasPayroll[0]['id'];
}

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
	"description"=>"Sub Allowances",
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
$last_day = date("Y-m-t",strtotime($first_day));
$period_date = ($_POST['period'])?$middle_day:$last_day;

$loans = $con->getData("SELECT * FROM loans WHERE staff_id = ".$_POST['id']." AND '$period_date' >= loan_effectivity");

$loan_period = "loan_monthly_".$_POST['period'];
foreach ($loans as $key => $loan) {		
	
	$payroll_deductions[] = array(
		"payroll_id"=>$payroll_id,
		"description"=>$loan['loan_description'],
		"description_field"=>"loan_".$loan['id'],
		"unit"=>"",		
		"amount"=>$loan[$loan_period],
		"system_log"=>"CURRENT_TIMESTAMP"
	);
	
};

# Tardiness
$tardiness = tardiness($con,$staff[0],$first_day,$last_day);
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"Tardiness",
	"description_field"=>"tardiness",
	"unit"=>$tardiness['tardiness'],
	"amount"=>$tardiness['amount'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

# AWOL
$absences = absences($con,$staff[0],$first_day,$last_day);
$payroll_deductions[] = array(
	"payroll_id"=>$payroll_id,
	"description"=>"AWOL",
	"description_field"=>"absences",
	"unit"=>$absences['absences'],
	"amount"=>$absences['amount'],
	"system_log"=>"CURRENT_TIMESTAMP"
);

$hasPayrollDeductions = $con->getData("SELECT * FROM payroll_deductions WHERE payroll_id = $payroll_id");

if (count($hasPayrollDeductions) == 0) {
	$con->table = "payroll_deductions";
	$con->insertDataMulti($payroll_deductions);
}

?>