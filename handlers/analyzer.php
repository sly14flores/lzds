<?php

class log_order {
	
	var $schedules;
	
	function __construct($con,$id) {
		
		$schedule = $con->getData("SELECT schedule_id FROM staffs WHERE id = $id");
		$schedules = $con->getData("SELECT * FROM schedules_details WHERE schedule_id = ".$schedule[0]['schedule_id']." ORDER BY id");
		
		foreach ($schedules as $key => $schedule) {
			
			unset($schedules[$key]['id']);
			unset($schedules[$key]['schedule_id']);
			unset($schedules[$key]['schedule_day']);
			$this->schedules[$schedule['schedule_day']] = $schedules[$key];
			
		}		
		
	}
	
	function allot($date,$log) {
		$allotment = null;

		$morning_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['morning_cutoff']);
		$lunch_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['lunch_break_cutoff']);
		$afternoon_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['afternoon_cutoff']);
		$tlog = strtotime($log);

		if ($tlog < $morning_cutoff) $allotment = array("morning_in"=>date("Y-m-d H:i:s",$tlog));
		if ( ($tlog >= $morning_cutoff) && ($tlog < $lunch_cutoff) ) $allotment = array("morning_out"=>date("Y-m-d H:i:s",$tlog));
		if ( ($tlog < $afternoon_cutoff) && ($tlog >= $lunch_cutoff) ) $allotment = array("afternoon_in"=>date("Y-m-d H:i:s",$tlog));
		if ($tlog >= $afternoon_cutoff) $allotment = array("afternoon_out"=>date("Y-m-d H:i:s",$tlog));
		
		return $allotment;
	}
}

class log_order_student {
	
	var $schedules;
	var $schedule_id;
	
	function __construct($con,$id) {
		
		$section = $con->getData("SELECT section FROM enrollments WHERE id = $id");
		$schedule = $con->getData("SELECT id FROM schedules WHERE section = ".$section[0]['section']);
		
		$this->schedule_id = $schedule[0]['id'];
		
		$schedules = $con->getData("SELECT * FROM schedules_details WHERE schedule_id = ".$schedule[0]['id']." ORDER BY id");		
		
		foreach ($schedules as $key => $schedule) {
			
			unset($schedules[$key]['id']);
			unset($schedules[$key]['schedule_id']);
			unset($schedules[$key]['schedule_day']);
			$this->schedules[$schedule['schedule_day']] = $schedules[$key];
			
		}		
		
	}
	
	function allot($date,$log) {
		$allotment = null;

		$morning_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['morning_cutoff']);
		$lunch_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['lunch_break_cutoff']);
		$afternoon_cutoff = strtotime("$date ".$this->schedules[date("D",strtotime($date))]['afternoon_cutoff']);
		$tlog = strtotime($log);

		if ($tlog < $morning_cutoff) $allotment = array("morning_in"=>date("Y-m-d H:i:s",$tlog));
		if ( ($tlog >= $morning_cutoff) && ($tlog < $lunch_cutoff) ) $allotment = array("morning_out"=>date("Y-m-d H:i:s",$tlog));
		if ( ($tlog < $afternoon_cutoff) && ($tlog >= $lunch_cutoff) ) $allotment = array("afternoon_in"=>date("Y-m-d H:i:s",$tlog));
		if ($tlog >= $afternoon_cutoff) $allotment = array("afternoon_out"=>date("Y-m-d H:i:s",$tlog));
		
		return $allotment;
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

function is_working_day($day) {
	
	$is_working_day = false;
	
	switch (date("D",strtotime($day))) {
		
		case "Mon":
			$is_working_day = true;
		break;
		
		case "Tue":
			$is_working_day = true;		
		break;
		
		case "Wed":
			$is_working_day = true;		
		break;
		
		case "Thu":
			$is_working_day = true;		
		break;
		
		case "Fri":
			$is_working_day = true;		
		break;
		
	}
	
	return $is_working_day;
	
}

function is_onleave_travel($con,$day) {
	
	$is_onleave_travel = false;
	
	$leaves = $con->getData("SELECT leave_description, leave_wholeday FROM leaves WHERE leave_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));		
	$tos = $con->getData("SELECT to_description, to_wholeday FROM travel_orders WHERE to_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));
	
	# on leave
	foreach ($leaves as $leave) {
		$is_onleave_travel = true;
	}

	# on travel order
	foreach ($tos as $to) {
		$is_onleave_travel = true;
	}
	
	return $is_onleave_travel;

}

function is_onleave_travel_am($con,$day) {
	
	$is_onleave_travel_am = false;
	
	$leaves = $con->getData("SELECT leave_description, leave_wholeday FROM leaves WHERE leave_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));		
	$tos = $con->getData("SELECT to_description, to_wholeday FROM travel_orders WHERE to_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));
	
	# on leave
	foreach ($leaves as $leave) {
		if ($leave['leave_wholeday'] == "AM") $is_onleave_travel_am = true;
	}

	# on travel order
	foreach ($tos as $to) {
		if ($to['to_wholeday'] == "AM") $is_onleave_travel_am = true;
	}
	
	return $is_onleave_travel_am;

}

function is_onleave_travel_pm($con,$day) {
	
	$is_onleave_travel_pm = false;
	
	$leaves = $con->getData("SELECT leave_description, leave_wholeday FROM leaves WHERE leave_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));		
	$tos = $con->getData("SELECT to_description, to_wholeday FROM travel_orders WHERE to_date = '$day' AND staff_id = ".staff_id($con,$_POST['rfid']));
	
	# on leave
	foreach ($leaves as $leave) {
		if ($leave['leave_wholeday'] == "PM") $is_onleave_travel_pm = true;
	}

	# on travel order
	foreach ($tos as $to) {
		if ($to['to_wholeday'] == "PM") $is_onleave_travel_pm = true;
	}
	
	return $is_onleave_travel_pm;

}

function is_absent($dtr,$day) {
	
	$is_absent = false;
	
	if (!is_working_day($day)) return $is_absent; # if Saturday or Sunday
	
	if (is_holiday($day)) return $is_absent; # except Holidays
	
	if ( (date("H:i:s",strtotime($dtr['morning_in'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['morning_out'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_in'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_out'])) == "00:00:00") ) $is_absent = true;
	
	return $is_absent;
	
};

function is_halfday_am($dtr,$day) {
	
	$is_halfday_am = false;
	
	if (!is_working_day($day)) return $is_halfday_am; # if Saturday or Sunday
	
	if (is_holiday($day)) return $is_halfday_am; # except Holidays
	
	if ( (date("H:i:s",strtotime($dtr['morning_in'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['morning_out'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_in'])) != "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_out'])) != "00:00:00") ) $is_halfday_am = true;
	
	return $is_halfday_am;
	
};

function is_halfday_pm($dtr,$day) {
	
	$is_halfday_pm = false;
	
	if (!is_working_day($day)) return $is_halfday_pm; # if Saturday or Sunday
	
	if (is_holiday($day)) return $is_halfday_pm; # except Holidays

	if ( (date("H:i:s",strtotime($dtr['morning_in'])) != "00:00:00") && (date("H:i:s",strtotime($dtr['morning_out'])) != "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_in'])) == "00:00:00") && (date("H:i:s",strtotime($dtr['afternoon_out'])) == "00:00:00") ) $is_halfday_pm = true;

	return $is_halfday_pm;

};

function is_holiday($day) {
	
	global $con;
	
	$holidays = $con->getData("SELECT * FROM holidays WHERE holiday_date = '$day'");
	
	$is_holiday = false;
	
	foreach ($holidays as $holiday) {
		$is_holiday = true;
	}
	
	return $is_holiday;
	
};

?>