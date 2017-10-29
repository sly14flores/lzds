<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once 'analyzer.php';

$con = new pdo_db("dtr");
$analyze = new log_order($con,$_POST['id']);

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

if (count($_Dtrs) == 0) generateDtr($con,$analyze);
$_Dtrs = $con->getData($sql);

foreach ($_Dtrs as $key => $dtr) {
	
	$_Dtrs[$key]['date'] = date("F j",strtotime($dtr['ddate']));
	$_Dtrs[$key]['day'] = date("l",strtotime($dtr['ddate']));
	$_Dtrs[$key]['morning_in'] = (date("H:i:s",strtotime($dtr['morning_in']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['morning_in']));
	$_Dtrs[$key]['morning_out'] = (date("H:i:s",strtotime($dtr['morning_out']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['morning_out']));
	$_Dtrs[$key]['afternoon_in'] = (date("H:i:s",strtotime($dtr['afternoon_in']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['afternoon_in']));
	$_Dtrs[$key]['afternoon_out'] = (date("H:i:s",strtotime($dtr['afternoon_out']))=="00:00:00")?"-":date("h:i:s A",strtotime($dtr['afternoon_out']));
	$_Dtrs[$key]['tardiness'] = ($dtr['tardiness'] =="00:00:00")?"-":$dtr['tardiness'];
	
	$_Dtrs[$key]['remarks'] = "";
	$leaves = $con->getData("SELECT leave_description, leave_wholeday FROM leaves WHERE leave_date = '".$dtr['ddate']."' AND staff_id = ".staff_id($con,$dtr['rfid']));
	foreach ($leaves as $leave) {
		$_Dtrs[$key]['remarks'] .= "Leave: ".$leave['leave_description']. ", ".$leave['leave_wholeday'];
	}
	if ($_Dtrs[$key]['remarks'] != "") $_Dtrs[$key]['remarks'] .= "; ";
	$tos = $con->getData("SELECT to_description, to_wholeday FROM travel_orders WHERE to_date = '".$dtr['ddate']."' AND staff_id = ".staff_id($con,$dtr['rfid']));
	foreach ($tos as $to) {
		$_Dtrs[$key]['remarks'] .= "Travel Order: ".$to['to_description']. ", ".$to['to_wholeday'];
	}		
	
}

header("Content-type: application/json");
echo json_encode($_Dtrs);

function generateDtr($con,$analyze) {

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
			"tardiness"=>"00:00:00",
			"system_log"=>"CURRENT_TIMESTAMP"
		);
		
		# tardiness
		$schedules = $analyze->schedules;
		$morning_in = "$day ".$schedules[date("l",strtotime($day))]['morning_in'];
		$leaves = $con->getData("SELECT leave_description, leave_wholeday FROM leaves WHERE leave_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));		
		$tos = $con->getData("SELECT to_description, to_wholeday FROM travel_orders WHERE to_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));
		
		if ( (date("H:i:s",strtotime($dtr['morning_in'])) != "00:00:00") && (strtotime($dtr['morning_in']) > strtotime($morning_in)) ) {
			$tardiness = strtotime($dtr['morning_in'])-strtotime($morning_in);
			$dtr['tardiness'] = gmdate('H:i:s',$tardiness);
		}
		
		# if absent
		
		# on leave
		if ($dtr['tardiness'] != "00:00:00") {
			foreach ($leaves as $leave) {
				if ($leave['leave_wholeday'] != "PM") $dtr['tardiness'] = "00:00:00";
			}
		}
			
		# on travel order
		if ($dtr['tardiness'] != "00:00:00") {
			foreach ($tos as $to) {
				if ($to['to_wholeday'] != "PM") $dtr['tardiness'] = "00:00:00";
			}
		}		
		
		$log = $con->insertData($dtr);
		
		$day = date("Y-m-d",strtotime("+1 Day",strtotime($day)));
		
	}

}

function staff_id($con,$rfid) {
	
	$staff_id = 0;
	
	$result = $con->getData("SELECT id FROM staffs WHERE rfid = $rfid");
	foreach ($result as $r) {
		$staff_id = $r['id'];
	}
	
	return $staff_id;
	
}

?>