<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

function getDescriptionObj($description) {
	
	$descriptions = array(
		array("name"=>"undefined","description"=>"-"),
		array("name"=>"monthly_payment","description"=>"Monthly Payment"),
		array("name"=>"down_payment","description"=>"Down Payment")
	);
	
	$obj = array("name"=>"undefined","description"=>"-");
	
	foreach ($descriptions as $i => $item) {
		
		if ($item['name'] == $description) $obj = $item;
		
	};
	
	return $obj;
	
};

function getMonthObj($month) {
	
	$months = array(
		array("no"=>"undefined", "name"=>"-"),
		array("no"=>"01", "name"=>"January"),
		array("no"=>"02", "name"=>"February"),
		array("no"=>"03", "name"=>"March"),
		array("no"=>"04", "name"=>"April"),
		array("no"=>"05", "name"=>"May"),
		array("no"=>"06", "name"=>"June"),
		array("no"=>"07", "name"=>"July"),
		array("no"=>"08", "name"=>"August"),
		array("no"=>"09", "name"=>"September"),
		array("no"=>"10", "name"=>"October"),
		array("no"=>"11", "name"=>"November"),
		array("no"=>"12", "name"=>"December")
	);
	
	$obj = array("no"=>"undefined", "name"=>"-");
	
	foreach ($months as $i => $item) {
		
		if ($item['no'] == $month) $obj = $item;
		
	};
	
	return $obj;
	
};	

$con = new pdo_db();

$payments = $con->getData("SELECT id, enrollment_id, description, payment_month, amount, official_receipt, payment_date FROM payments WHERE enrollment_id = ".$_POST['enrollment_id']);
foreach ($payments as $i => $payment) {
	
	$payments[$i]['description'] = getDescriptionObj($payment['description']);
	$payments[$i]['payment_month'] = getMonthObj($payment['payment_month']);
	$payments[$i]['payment_date_str'] = date("F j, Y",strtotime($payments[$i]['payment_date']));
	
};

echo json_encode($payments);

?>