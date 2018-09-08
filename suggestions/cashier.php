<?php

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, school_year FROM school_years";
$get_school_years = $con->getData($sql);

$school_years = [];
$school_years[] = array("id"=>0, "school_year"=>"SY");

foreach ($get_school_years as $school_year) {
	
	$school_years[] = $school_year;
	
};

$sql = "SELECT id, description FROM grade_levels";
$get_levels = $con->getData($sql);

$levels = [];
$levels[] = array("id"=>0, "description"=>"All");

foreach ($get_levels as $level) {
	
	$levels[] = $level;
	
};

echo json_encode(array("school_years"=>$school_years,"levels"=>$levels));

?>