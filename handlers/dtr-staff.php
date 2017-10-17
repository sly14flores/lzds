<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("dtr");

$month = $_POST['year']."-".$_POST['month']['month'];
$sql = "SELECT * FROM dtr WHERE rfid = ".$_POST['rfid']." AND ddate LIKE '$month-%'";

$_Dtrs = $con->getData($sql);

if (count($_Dtrs) == 0) generateDtr($con);
$_Dtrs = $con->getData($sql);

foreach ($_Dtrs as $key => $dtr) {
	
	$_Dtrs[$key]['date'] = date("F j",strtotime($dtr['ddate']));
	$_Dtrs[$key]['day'] = date("l",strtotime($dtr['ddate']));
	$_Dtrs[$key]['morning_in'] = date("h:i:s A",strtotime($dtr['morning_in']));
	$_Dtrs[$key]['morning_out'] = date("h:i:s A",strtotime($dtr['morning_out']));
	$_Dtrs[$key]['afternoon_in'] = date("h:i:s A",strtotime($dtr['afternoon_in']));
	$_Dtrs[$key]['afternoon_out'] = date("h:i:s A",strtotime($dtr['afternoon_out']));
	
}

header("Content-type: application/json");
echo json_encode($_Dtrs);

function generateDtr($con) {

	$start = $_POST['year']."-".$_POST['month']['month']."-01";
	$end = date("Y-m-t",strtotime($start));

	$day = $start;

	while (strtotime($day) <= strtotime($end)) {
		
		$dtr = array(
			"rfid"=>$_POST['rfid'],
			"ddate"=>$day,
			"morning_in"=>"$day 00:00:00",
			"morning_out"=>"$day 00:00:00",
			"afternoon_in"=>"$day 00:00:00",
			"afternoon_out"=>"$day 00:00:00",
			"system_log"=>"CURRENT_TIMESTAMP"
		);
		$log = $con->insertData($dtr);
		
		$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
		
	}

}

?>