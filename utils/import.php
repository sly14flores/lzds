<?php

require_once '../db.php';

$fees_indexes = array(
	"enrollee_tuition_fee"=>1,
	"enrollee_books"=>2,
	"enrollee_pc"=>3,
	"enrollee_science_lab"=>6,
	"enrollee_athletic_fee"=>11,
	"enrollee_forms_fee"=>12,
	"enrollee_handbook_fee"=>13,
	"enrollee_aircon_fee"=>18,
	"enrollee_reg"=>4,
	"enrollee_med"=>5,
	"enrollee_lib"=>7,
	"enrollee_id_fee"=>8,
	"enrollee_insurance"=>9,
	"enrollee_papers"=>10,
	"enrollee_luprisa"=>14,
	"enrollee_energy_fee"=>15,
	"enrollee_collection_fee"=>16,
	"enrollee_handouts_fee"=>17
);

$enrollee_stat = array("Regular","Regular","Transferee");

$relationships = array(
	"Father"=>array(
		"student_id"=>0,
		"relationship"=>"Father",
		"full_name"=>"enrollee_father",
		"last_name"=>"enrollee_father_lastname",
		"first_name"=>"enrollee_father_firstname",
		"middle_name"=>"enrollee_father_middlename",
		"ext_name"=>"enrollee_father_extname",
		"occupation"=>"enrollee_father_job",
		"monthly_income"=>"enrollee_father_income",
		"old_table_pk"=>"enrollee_id"
	),
	"Mother"=>array(
		"student_id"=>0,	
		"relationship"=>"Mother",	
		"full_name"=>"enrollee_mother",
		"last_name"=>"enrollee_mother_lastname",
		"first_name"=>"enrollee_mother_firstname",
		"middle_name"=>"enrollee_mother_middlename",
		"ext_name"=>"enrollee_mother_extname",
		"maiden_name"=>"enrollee_mother_maiden",
		"occupation"=>"enrollee_mother_job",
		"monthly_income"=>"enrollee_mother_income",
		"old_table_pk"=>"enrollee_id"		
	),
	"Guardian"=>array(
		"student_id"=>0,	
		"relationship"=>"Guardian",	
		"full_name"=>"enrollee_guardian",
		"last_name"=>"enrollee_guardian_firstname",
		"first_name"=>"enrollee_guardian_lastname",
		"middle_name"=>"enrollee_guardian_middlename",
		"ext_name"=>"enrollee_guardian_extname",
		"occupation"=>"enrollee_guardian_job",
		"monthly_income"=>"enrollee_guardian_income",
		"old_table_pk"=>"enrollee_id"
	)
);

$results = [];

$student = [];
$student = array(
	"school_id"=>$results[0]["enrollee_fid"],
	"old_school_type"=>$results[0]["enrollee_old_school_type"],
	"old_school_name"=>$results[0]["enrollee_old_school_name"],
	"firstname"=>$results[0]["enrollee_fname"],
	"lastname"=>$results[0]["enrollee_lname"],
	"middlename"=>$results[0]["enrollee_mname"],
	"gender"=>$results[0]["enrollee_sex"],
	"student_status"=>$enrollee_stat[$results[0]["enrollee_stat"]],
	"contact_no"=>$results[0]["enrollee_contact"],
	"home_address"=>$results[0]["enrollee_address"],
	"date_of_birth"=>$results[0]["enrollee_dob"],
	"place_of_birth"=>$results[0]["enrollee_pob"],
	"religion"=>$results[0]["enrollee_religion"],
	"indigenous"=>$results[0]["enrollee_indigenous"],
	"ethnicity"=>$results[0]["enrollee_ethnicity"],
	"mother_tongue"=>$results[0]["enrollee_tongue"],
	"dialect"=>$results[0]["enrollee_languages"],
	"lrn"=>$results[0]["enrollee_lrn"],
	"siblings_no"=>$results[0]["enrollee_no_siblings"],
	"email_address"=>$results[0]["enrollee_email"],
	"old_table_pk"=>$results[0]["enrollee_id"]
);

/*
** parents/guardians
*/

$parents_guardians = [];
foreach ($relationships as $i => $d) {
	
	foreach	($d as $ii => $dd) {
		
		$parents_guardians[][$ii] = $results[0][$dd];
		
	}	
	
}

/*
** enrollments
*/

$enrollments = [];
$enrollments[] = (
	"student_id"=>0,
	"grade"=>$result["enrollee_grade"],
	"section"=>$result["enrollee_section"],
	"enrollment_school_year"=>$result["enrollee_sy"],
	"enrollment_date"=>$result["enrollee_date"],	
	"registered_online"=>$result["registered_online"],
	"enrollee_rn"=>$result["enrollee_rn"],
	"old_table_pk"=>$result["enrollee_id"]
);

/*
** down payment
*/

$down_payment = [];
$down_payment = array(
	"enrollment_id"=>0,
	"description"=>"Down Payment",
	"amount"=>$results[0]["enrollee_down_payment"],
	"official_receipt"=>$results[0]["down_payment_or"],
	"payment_date"=>$results[0]["down_payment_date"],
	"old_table_pk"=>$results[0]["enrollee_id"]
);

/*
** discount
*/

$student_discount = [];
$student_discount = array(
	"enrollment_id"=>0,
	"amount"=>$results[0]["enrollee_discount"],
	"old_table_pk"=>$results[0]["enrollee_id"]
);	

?>