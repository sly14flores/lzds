<?php

function current_sy($con) {

	$school_years = $con->getData("SELECT id, school_year FROM school_years");

	$current_sy = date("Y-").date("y",strtotime("+1 Year",strtotime(date("Y-m-d"))));
	
	foreach ($school_years as $school_year) {
		
		if ($school_year['school_year'] == $current_sy) return $school_year;
		
	};	
	
}

?>