<?php

require_once '../db2.php';

$con = new pdo_db("lzdssystem","students_fees");

$results = $con->getData("SELECT * FROM enrollments WHERE enrollment_school_year = 5");

#174
$i = 174;
$total = count($results);

import($results[$i]);

function import($result) {

global $con, $results, $total, $i;

$c = $i+1;


	$sql = "SELECT fee_items.id, fee_items.amount FROM fee_items LEFT JOIN fees on fee_items.fee_id = fees.id WHERE fees.school_year = 5 AND fees.id = 91 AND fee_items.level = ".$result['grade'];
	$news_paper = ($con->getData($sql))[0];
	
	$check = $con->getData("SELECT * FROM students_fees WHERE enrollment_id = $result[id] AND fee_item_id = $news_paper[id]");
	if (count($check)) {
		$delete = $con->deleteData(array("id"=>implode(",",[$check[0]['id']])));		
	}
	
	$data = array("enrollment_id"=>$result['id'],"fee_item_id"=>$news_paper['id'],"amount"=>$news_paper['amount'],"old_table_pk"=>$result['old_table_pk']);
	
	$insert = $con->insertData($data);
	
$percent = ceil($c*100/$total);

echo "Updated $result[id] ($c/$total)...$percent% done\n";

$i++;

// if ($i<$total) import($results[$i]);
// else echo "$percent% done...\n";
echo "$percent% done...\n";

}

?>