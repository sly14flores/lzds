<?php

require_once '../db2.php';

$source = new pdo_db("lzds");
$destination = new pdo_db("lzdssystem","students");

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

$enrollee_stat = array("Regular","Regular","Transferee");

$enrollee_section = array(
	2=>"Omega",
	3=>"Faith",
	4=>"Love",
	5=>"Peace",
	6=>"Hope",
	7=>"Joy",
	8=>"Charity",
	10=>"St. Matthew",
	11=>"St. Mark",
	12=>"St. Luke",
	13=>"St. John",
	14=>"Alpha",
	15=>"St. Peter"
);

$grade = array(
	101=>"Nursery",
	102=>"Kindergarten",
	1=>"Grade One",
	2=>"Grade Two",
	3=>"Grade Three",
	4=>"Grade Four",
	5=>"Grade Five",
	6=>"Grade Six",
	7=>"Grade Seven",
	8=>"Grade Eight",
	9=>"Grade Nine",
	10=>"Grade Ten",
	11=>"Grade Eleven",
	12=>"Grade Twelve"
);

$relationships = array(
	array(
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
	array(
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
	array(
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

// $results = $source->getData("SELECT * FROM enrollees WHERE enrollee_id IN (306,633,1234,1590) ORDER BY enrollee_lname, enrollee_fname, enrollee_mname, enrollee_sy");
$results = $source->getData("SELECT * FROM enrollees WHERE enrollee_id IN (306,633,1234,1590) ORDER BY enrollee_sy");

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

var_dump($student);

$import_student = $destination->insertData($student);
$student_id = $destination->insertId;

/*
** parents/guardians
*/

$destination->table = "parents_guardians";
$parents_guardians = [];
foreach ($relationships as $i => $d) {
	
	foreach	($d as $ii => $dd) {

		if ($ii == "relationship") {
			$parents_guardians[$i][$ii] = $dd;
		} else {
			if ($ii == "student_id") $parents_guardians[$i][$ii] = $student_id;
			else $parents_guardians[$i][$ii] = $results[0][$dd];
		}
		
	}	
	
}

var_dump($parents_guardians);

foreach ($parents_guardians as $pg) {
	
	$import_parent_guardian = $destination->insertData($pg);

}

/*
** enrollments
*/

$enrollments = [];

foreach ($results as $key => $result) {
	$enrollments[] = array(
		"student_id"=>$student_id,
		"grade"=>$grade[$result["enrollee_grade"]],
		"section"=>(isset($enrollee_section[$result["enrollee_section"]])?$enrollee_section[$result["enrollee_section"]]:''),
		"enrollment_school_year"=>$result["enrollee_sy"].date("-y",strtotime("+1 Year",strtotime($result["enrollee_sy"]."-01-01"))),
		"enrollment_date"=>$result["enrollee_date"],	
		"registered_online"=>($result["registered_online"]=='yes')?1:0,
		"enrollee_rn"=>$result["enrollee_rn"],
		"old_table_pk"=>$result["enrollee_id"]
	);
}

var_dump($enrollments);

/*
** student fees
*/

$students_fees = [];

foreach ($results as $key => $result) {
	
foreach ($fees_indexes as $i => $fi) {
	$students_fees[] = array(
		"enrollment_id"=>0,
		"fee_item_id"=>$fi,
		"amount"=>$result[$i],
		"old_table_pk"=>$result["enrollee_id"]
	);	
}

}

var_dump($students_fees);

/*
** down payment
*/

$down_payments = [];

foreach ($results as $key => $result) {
	$down_payments[] = array(
		"enrollment_id"=>0,
		"description"=>"Down Payment",
		"amount"=>$result["enrollee_down_payment"],
		"official_receipt"=>$result["down_payment_or"],
		"payment_date"=>$result["down_payment_date"],
		"old_table_pk"=>$result["enrollee_id"]
	);
}

var_dump($down_payments);


/*
** discount
*/

$student_discounts = [];
foreach ($results as $key => $result) {
	$student_discounts[] = array(
		"enrollment_id"=>0,
		"amount"=>$result["enrollee_discount"],
		"old_table_pk"=>$result["enrollee_id"]
	);
}

var_dump($student_discounts);

foreach ($enrollments as $enrollment) {
	
	// enrollment
	$destination->table = "enrollments";
	$import_enrollments = $destination->insertData($enrollment);
	$enrollment_id = $destination->insertId;
	
	// downpayment
	$destination->table = "students_fees";
	foreach ($students_fees as $k => $sf) {
		
		if ($sf['old_table_pk'] == $enrollment['old_table_pk']) {

			$students_fees[$k]['enrollment_id'] = $enrollment_id;
			$import_student_fee = $destination->insertData($students_fees[$k]);
		
		}
		
	}
	
	// monthly payment	
	
	// discount
	$destination->table = "students_discounts";
	foreach ($student_discounts as $k => $sd) {
		
		if ($sd['old_table_pk'] == $enrollment['old_table_pk']) {

			$student_discounts[$k]['enrollment_id'] = $enrollment_id;
			$import_student_discount = $destination->insertData($student_discounts[$k]);
		
		}
		
	}	

}

?>