<?php

function current_sy($con) {

	$school_years = $con->getData("SELECT id, school_year FROM school_years");

	$current_sy = date("Y-").date("y",strtotime("+1 Year",strtotime(date("Y-m-d"))));
	
	foreach ($school_years as $school_year) {
		
		if ($school_year['school_year'] == $current_sy) return $school_year;
		
	};	
	
}

function tardiness($con,$staff,$first_day,$last_day) {
	
	$tardiness = $con->getData("SELECT * FROM dtr WHERE rfid = '".$staff['rfid']."' AND ddate >= '$first_day' AND ddate <= '$last_day'");
	
	$total_tardiness = "00:00:00";
	
	foreach ($tardiness as $t) {
		
		if ($t['tardiness'] != "00:00:00") {
			
			$total_tardiness = addTwoTimes($total_tardiness,$t['tardiness']);
			
		}
		
	}
	
	# amount computation
	
	return array("tardiness"=>$total_tardiness,"amount"=>0);
	
}

function absences($con,$staff,$first_day,$last_day) {
	
	$absences = $con->getData("SELECT * FROM dtr WHERE rfid = '".$staff['rfid']."' AND ddate >= '$first_day' AND ddate <= '$last_day'");
	
	$total_absences = 0;
	
	foreach ($absences as $a) {
		
		if ($a['absent'] > 0) {
			
			$total_absences += $a['absent'];
			
		}
		
	}

	# amount computation	
	
	return array("absences"=>$total_absences,"amount"=>0);
	
}

function addTwoTimes($time1 = "00:00:00", $time2 = "00:00:00") {
	
	$time2_arr = [];
	$time1 = $time1;
	$time2_arr = explode(":", $time2);
	
	# Hour
	if(isset($time2_arr[0]) && $time2_arr[0] != ""){
		$time1 = $time1." +".$time2_arr[0]." hours";
		$time1 = date("H:i:s", strtotime($time1));
	}
	
	# Minutes
	if(isset($time2_arr[1]) && $time2_arr[1] != ""){
		$time1 = $time1." +".$time2_arr[1]." minutes";
		$time1 = date("H:i:s", strtotime($time1));
	}
	
	# Seconds
	if(isset($time2_arr[2]) && $time2_arr[2] != ""){
		$time1 = $time1." +".$time2_arr[2]." seconds";
		$time1 = date("H:i:s", strtotime($time1));
	}

	return date("H:i:s", strtotime($time1));
		
}

?>