<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once 'analyzer.php';

$con = new pdo_db("dtr");

$month = $_POST['year']."-".$_POST['month']['month'];

$sql = "SELECT * FROM dtr WHERE rfid = '".$_POST['rfid']."' AND ddate LIKE '$month-%'";
$_Dtrs = $con->getData($sql);

if ($_POST['option']) {
	foreach ($_Dtrs as $key => $dtr) {
		$sql = "DELETE FROM dtr WHERE id = $dtr[id]";
		$delete = $con->query($sql);
	}
	$sql = "SELECT * FROM dtr WHERE rfid = '".$_POST['rfid']."' AND ddate LIKE '$month-%'";
	$_Dtrs = $con->getData($sql);	
}

if (count($_Dtrs) == 0) generateDtr($con);
$_Dtrs = $con->getData($sql);

foreach ($_Dtrs as $key => $dtr) {
	
	$_Dtrs[$key]['date'] = date("F j",strtotime($dtr['ddate']));
	$_Dtrs[$key]['day'] = date("l",strtotime($dtr['ddate']));
	$_Dtrs[$key]['morning_in'] = (date("H:i:s",strtotime($dtr['morning_in']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['morning_in']));
	$_Dtrs[$key]['morning_out'] = (date("H:i:s",strtotime($dtr['morning_out']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['morning_out']));
	$_Dtrs[$key]['afternoon_in'] = (date("H:i:s",strtotime($dtr['afternoon_in']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['afternoon_in']));
	$_Dtrs[$key]['afternoon_out'] = (date("H:i:s",strtotime($dtr['afternoon_out']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['afternoon_out']));
	
}

header("Content-type: application/json");
echo json_encode($_Dtrs);

function generateDtr($con) {

	$analyze = new log_order($con,$_POST['id']);

	$start = $_POST['year']."-".$_POST['month']['month']."-01";
	$end = date("Y-m-t",strtotime($start));

	$day = $start;

	while (strtotime($day) <= strtotime($end)) {
		
		$logs = $con->getData("SELECT * FROM attendances WHERE rfid = '".$_POST['rfid']."' AND time_log LIKE '$day%'");

		/*
		** analyze timein/timeout
		*/
		$analyzed = array(
			"morning_in"=>"$day 00:00:00",
			"morning_out"=>"$day 00:00:00",
			"afternoon_in"=>"$day 00:00:00",
			"afternoon_out"=>"$day 00:00:00"
		);
		foreach ($logs as $log) {
			$allotment = $analyze->allot($day,$log['time_log']);
			$prop = array_keys($allotment);
			$analyzed[$prop[0]] = $allotment[$prop[0]];
		};		

		$dtr = array(
			"rfid"=>$_POST['rfid'],
			"ddate"=>$day,
			"morning_in"=>$analyzed['morning_in'],
			"morning_out"=>$analyzed['morning_out'],
			"afternoon_in"=>$analyzed['afternoon_in'],
			"afternoon_out"=>$analyzed['afternoon_out'],
			"system_log"=>"CURRENT_TIMESTAMP"
		);
		$log = $con->insertData($dtr);
		
		$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
		
	}

}

?>