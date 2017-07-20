<?php

$school_years = array(
	"2013-14"=>1,
	"2014-15"=>2,
	"2015-16"=>3,
	"2016-17"=>4,
	"2017-18"=>5
);

require_once '../db.php';

$con = new pdo_db("enrollments");

$results = $con->getData("SELECT * FROM enrollments");

foreach ($results as $result) {

	$con->query("UPDATE enrollments SET enrollment_school_year = '".$school_years[$result['enrollment_school_year']]."' WHERE id = ".$result['id']);
	
}

?>