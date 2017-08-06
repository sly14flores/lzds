<?php

$fees_indexes = array(
	"enrollee_tuition_fee"=>1,
	"enrollee_books"=>2,
	"enrollee_pc"=>3,
	"enrollee_reg"=>4,
	"enrollee_med"=>5,
	"enrollee_science_lab"=>6,
	"enrollee_lib"=>7,
	"enrollee_id_fee"=>8,
	"enrollee_insurance"=>9,
	"enrollee_papers"=>10,	
	"enrollee_athletic_fee"=>11,
	"enrollee_forms_fee"=>12,
	"enrollee_handbook_fee"=>13,
	"enrollee_luprisa"=>14,
	"enrollee_energy_fee"=>15,
	"enrollee_collection_fee"=>16,	
	"enrollee_handouts_fee"=>17,	
	"enrollee_aircon_fee"=>18
);

$fees_indexes_description = array(
	"enrollee_tuition_fee"=>"Tuition Fee",
	"enrollee_books"=>"Books",
	"enrollee_pc"=>"Computer/Internet",
	"enrollee_reg"=>"Registration Fee",
	"enrollee_med"=>"Medical/Dental",
	"enrollee_science_lab"=>"Science Lab",
	"enrollee_lib"=>"Library",
	"enrollee_id_fee"=>"ID",
	"enrollee_insurance"=>"Insurance",
	"enrollee_papers"=>"Test Papers",	
	"enrollee_athletic_fee"=>"Athletic Fee",
	"enrollee_forms_fee"=>"Forms",
	"enrollee_handbook_fee"=>"Handbook",
	"enrollee_luprisa"=>"LUPRISA",
	"enrollee_energy_fee"=>"Energy Fee",
	"enrollee_collection_fee"=>"Collection / Processing Charges",
	"enrollee_handouts_fee"=>"Hand-Outs/SBO",	
	"enrollee_aircon_fee"=>"Aircon Fee"
);

require_once '../db.php';

$con = new pdo_db("students_fees");

$results = $con->getData("SELECT * FROM students_fees");


foreach ($results as $result) {	
	$enrollment = $con->getData("SELECT grade, enrollment_school_year FROM enrollments WHERE id = ".$result['enrollment_id']);
	$description = $con->getData("SELECT description FROM fees WHERE id = ".$result['fee_item_id']);
	if (count($description)) {
		$fee_item_id = $con->getData("SELECT fee_items.id FROM fee_items LEFT JOIN fees ON fee_items.fee_id = fees.id WHERE fees.description = '".$description[0]['description']."' AND fees.school_year = ".$enrollment[0]['enrollment_school_year']." AND fee_items.level = ".$enrollment[0]['grade']);
		$con->query("UPDATE students_fees SET fee_item_id = ".$fee_item_id[0]['id']." WHERE id = ".$result['id']);
	}
}

?>