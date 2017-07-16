<?php

$_grade = array(
	"Nursery"=>1,
	"Kindergarten"=>2,
	"Grade One"=>3,
	"Grade Two"=>4,
	"Grade Three"=>5,
	"Grade Four"=>6,
	"Grade Five"=>7,
	"Grade Six"=>8,
	"Grade Seven"=>9,
	"Grade Eight"=>10,
	"Grade Nine"=>11,
	"Grade Ten"=>12,
	"Grade Eleven"=>13,
	"Grade Twelve"=>14
);

$_enrollee_section = array(
	""=>"",
	"Omega"=>2,
	"Faith"=>3,
	"Love"=>4,
	"Peace"=>6,
	"Hope"=>7,
	"Joy"=>5,
	"Charity"=>8,
	"St. Matthew"=>9,
	"St. Mark"=>10,
	"St. Luke"=>11,
	"St. John"=>12,
	"Alpha"=>1,
	"St. Peter"=>13
);

require_once '../db.php';

$con = new pdo_db("enrollments");

$results = $con->getData("SELECT * FROM enrollments");

foreach ($results as $result) {

	$con->query("UPDATE enrollments SET grade = '".$_grade[$result['grade']]."' WHERE id = ".$result['id']);
	$con->query("UPDATE enrollments SET section = '".$_enrollee_section[$result['section']]."' WHERE id = ".$result['id']);
	
}

?>