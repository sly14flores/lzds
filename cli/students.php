<?php

require_once '../db2.php';

$source = new pdo_db("lzds");
$destination = new pdo_db("lzdssystem","students");

$start = 1740;
$end = 1776;

$results_all = $source->getData("SELECT * FROM enrollees ORDER BY enrollee_dob, enrollee_lname, enrollee_sy");

$results = [];
foreach ($results_all as $i => $result_all) {
	if ($i<$start) continue;
	if ($i>$end) continue;
	$results[]=$result_all;
}
	
$merge = [];
$merges = [];
foreach($results as $i => $result) {
	$merge[] = $result;
	if (!isset($results[$i+1])) {
		$merges[] = $merge;
		break;
	}
	$next = $results[$i+1];
	if (($result['enrollee_fname'] != $next['enrollee_fname']) || ($result['enrollee_dob'] != $next['enrollee_dob'])) {
		$merges[] = $merge;
		$merge = [];
	};
};

$index = 0;
$total = count($merges);

import($merges[$index]);

function import($results) {

global $source, $destination, $merges, $index, $total;

$c = $index+1;

/*
** setup
*/

$school_years = array(
	"2013-14"=>1,
	"2014-15"=>2,
	"2015-16"=>3,
	"2016-17"=>4,
	"2017-18"=>5
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
	15=>"St. Peter",
	16=>"Alpha",
	17=>"Genesis",
	18=>"St.Peter (ABM)",
	19=>"St. Peter (GAS)",
	20=>"St. Peter (STEM)",
	21=>"St. Paul (GAS)",
	22=>"St. Paul (ABM)",
	23=>"St. Paul (STEM)"
);

$_enrollee_section = array(
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
	"St. Peter"=>13,
	"Genesis"=>14,
	"St.Peter (ABM)"=>15,
	"St. Peter (GAS)"=>16,
	"St. Peter (STEM)"=>17,
	"St. Paul (GAS)"=>18,
	"St. Paul (ABM)"=>19,
	"St. Paul (STEM)"=>20	
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

/*
** students
*/
$student = [];
$student = array(
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

$destination->table = "students";
$import_student = $destination->insertData($student);
$student_id = $destination->insertId;

/*
** parents_guardians
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
	
};

foreach ($parents_guardians as $pg) {
	
	$import_parent_guardian = $destination->insertData($pg);

};

/*
** enrollments
*/
$enrollments = [];
foreach ($results as $key => $result) {
	$enrollment_school_year = $result["enrollee_sy"].date("-y",strtotime("+1 Year",strtotime($result["enrollee_sy"]."-01-01")));
	$enrollments[] = array(
		"student_id"=>$student_id,
		"school_id"=>$result["enrollee_fid"],		
		"grade"=>$_grade[$grade[$result["enrollee_grade"]]],
		"section"=>(isset($enrollee_section[$result["enrollee_section"]])?$_enrollee_section[$enrollee_section[$result["enrollee_section"]]]:0),
		"enrollment_school_year"=>$school_years[$enrollment_school_year],
		"enrollment_date"=>$result["enrollee_date"],	
		"registered_online"=>($result["registered_online"]=='yes')?1:0,
		"enrollee_rn"=>$result["enrollee_rn"],
		"old_table_pk"=>$result["enrollee_id"]
	);
};

$destination->table = "enrollments";
foreach ($enrollments as $enrollment) {

	// enrollment
	$import_enrollments = $destination->insertData($enrollment);
	$enrollment_id = $destination->insertId;	

};

$percent = ceil($c*100/$total);

echo "Imported $c/$total...$percent% done\n";

$index++;

if ($index<$total) import($merges[$index]);
else echo "$percent% done...\n";

}

?>