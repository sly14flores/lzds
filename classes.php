<?php

function current_sy($con) {
	
	$sy_cutoff = date("Y-04-01");
	$sy_now = date("Y-m-d");
	
	$school_years = $con->getData("SELECT id, school_year FROM school_years");

	if (strtotime($sy_now) >= strtotime($sy_cutoff)) $current_sy = date("Y-").date("y",strtotime("+1 Year",strtotime(date("Y-m-d"))));
	else $current_sy = date("Y-",strtotime("-1 Year",strtotime(date("Y-m-d")))).date("y");
	
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
	$hms = explode(":",$total_tardiness);
	$h = (int)$hms[0];
	$m = (int)$hms[1];
	$s = (int)$hms[2];
	
	$per_hour = 60;
	$per_minute = 1;	
	$per_second = 100/60;

	$hd = $h*$per_hour;
	$md = $m*$per_minute;
	$sd = $s*$per_second;
	
	$amount = $hd+$md+$sd;
	
	return array("tardiness"=>$total_tardiness,"amount"=>$amount);
	
}

function absences($con,$staff,$first_day,$last_day) {
	
	$absences = $con->getData("SELECT * FROM dtr WHERE rfid = '".$staff['rfid']."' AND ddate >= '$first_day' AND ddate <= '$last_day'");
	
	$total_absences = 0;
	
	foreach ($absences as $a) {
		
		if ($a['absent'] > 0) {
			
			$total_absences += $a['absent'];
			
		}
		
		if ($a['is_halfday'] > 0) {
			
			$total_absences += .5;
			
		}	
		
	}

	# amount computation
	$basic_pay = $staff['basic_pay'];
	$per_day = $basic_pay/22;

	$amount = 0;
	$amount = $total_absences*$per_day;
	
	return array("absences"=>$total_absences,"amount"=>$amount);
	
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
		
};

function parent_guardian($con,$student_id) {
	
	$pg = null;
	
	$pgs = $con->getData("SELECT * FROM parents_guardians WHERE student_id = $student_id");
	// var_dump($pgs);
	foreach ($pgs as $value) {
		if ( ($value['first_name'] == "") && ($value['last_name'] == "") ) {
			continue;
		} else {
			$pg = $value['first_name']." ".$value['last_name'];
		}
		if ($pg != null) break;
	};

	if ($pg == null) {
		
		foreach ($pgs as $value) {			
			if ($value['full_name'] == "") {
				continue;
			} else {
				$pg = $value['full_name'];
			}
			if ($pg != null) break;			
		};		
		
	};
	
	return $pg;
	
};

?>