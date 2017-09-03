<?php

require_once '../db2.php';

$src = new pdo_db("lzds");
$dst = new pdo_db("lzdssystem","payments");

// $start = 1;
// $end = 1789;

// $results = $dst->getData("SELECT * FROM enrollments WHERE id BETWEEN $start AND $end");
$results = $dst->getData("SELECT * FROM enrollments");

$i = 0;
$total = count($results);

import($results[$i]);

function import($result) {

global $src, $dst, $results, $total, $i;

$c = $i+1;

if ($result['old_table_pk'] != null) {

	$sql = "SELECT * FROM payments WHERE enrollment_id = ".$result['id'];
	$payments = $dst->getData($sql);

	foreach ($payments as $payment) {
		
		$delete = $dst->deleteData(array("id"=>implode(",",[$payment['id']])));
		
	};

	$sql = "SELECT enrollee_down_payment, down_payment_or, down_payment_date, enrollee_date FROM enrollees WHERE enrollee_id = ".$result['old_table_pk'];
	$dp = $src->getData($sql);

	if (count($dp)) {
		
		$down_payment = array(
			"enrollment_id"=>$result['id'],
			"description"=>"down_payment",
			"payment_month"=>date("m",strtotime($dp[0]['enrollee_date'])),
			"amount"=>$dp[0]['enrollee_down_payment'],
			"official_receipt"=>$dp[0]['down_payment_or'],
			"payment_date"=>$dp[0]['down_payment_date'],
			"old_table_pk"=>$result['old_table_pk'],
			"system_log"=>"CURRENT_TIMESTAMP"
		);

		$insert = $dst->insertData($down_payment);
		
	}

	$sql = "SELECT * FROM payments WHERE payment_sid = ".$result['old_table_pk']." ORDER BY payment_id";
	$mp = $src->getData($sql);

	if (count($mp)) {

	$monthly_payments = [];
	foreach ($mp as $m) {
		
		$monthly_payments[] = array(
			"enrollment_id"=>$result['id'],
			"description"=>"monthly_payment",
			"payment_month"=>str_pad($m['payment_month'],2,"0",STR_PAD_LEFT),
			"amount"=>$m['payment_amount'],
			"official_receipt"=>$m['payment_or'],
			"payment_date"=>$m['payment_date'],
			"old_table_pk"=>$result['old_table_pk'],
			"system_log"=>"CURRENT_TIMESTAMP"
		);	
		
	};

	$insertMulti = $dst->insertDataMulti($monthly_payments);

	};
	
} else {
	
	$log = fopen("payments-logs.txt","a+");
	fwrite($log,"$result[id] has null old_table_pk");
	
}

$percent = ceil($c*100/$total);

echo "Imported $result[id] ($c/$total)...$percent% done\n";

$i++;

if ($i<$total) import($results[$i]);
else echo "$percent% done...\n";

}

?>