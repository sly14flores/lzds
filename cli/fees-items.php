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

$school_years = array(
	"2013-14"=>1,
	"2014-15"=>2,
	"2015-16"=>3,
	"2016-17"=>4,
	"2017-18"=>5
);

require_once '../db2.php';

$src = new pdo_db("lzds");
$dst = new pdo_db("lzdssystem","students_fees");

$start = 1701;
$end = 1792;

$results = $dst->getData("SELECT * FROM enrollments WHERE id BETWEEN $start AND $end");

$i = 0;
$total = count($results);

import($results[$i]);

function import($result) {
	
global $fees_indexes, $fees_indexes_description, $grade, $_grade, $school_years, $src, $dst, $results, $total, $i;

$c = $i+1;

if ($result['old_table_pk'] != null) {
	
	$sql = "SELECT * FROM students_fees WHERE enrollment_id = ".$result['id'];
	$sfs = $dst->getData($sql);

	foreach ($sfs as $sf) {
		
		$delete = $dst->deleteData(array("id"=>implode(",",[$sf['id']])));
		
	};
	
	$sql = "SELECT * FROM enrollees WHERE enrollee_id = ".$result['old_table_pk'];
	$students_fees_src = $src->getData($sql);
	
	$students_fees = [];

	foreach ($students_fees_src as $key => $sfs) {
		$enrollment_school_year = $sfs["enrollee_sy"].date("-y",strtotime("+1 Year",strtotime($sfs["enrollee_sy"]."-01-01")));
		foreach ($fees_indexes as $ii => $fi) {
			$fees_items = $dst->getData("SELECT fee_items.id FROM fee_items LEFT JOIN fees ON fee_items.fee_id = fees.id WHERE fees.description = '".$fees_indexes_description[$ii]."' AND fees.school_year = ".$school_years[$enrollment_school_year]." AND fee_items.level = ".$_grade[$grade[$sfs["enrollee_grade"]]]);
			$students_fees[] = array(
				"enrollment_id"=>$result['id'],
				"fee_item_id"=>$fees_items[0]['id'],
				"amount"=>$sfs[$ii],
				"old_table_pk"=>$result['old_table_pk']
			);	
		}
	}

	$dst->table = "students_fees";
	foreach ($students_fees as $data) {
	
		$insert = $dst->insertData($data);
	
	}
	
	# students_discounts
	$dst->table = "students_discounts";
	$student_discount = array(
		"enrollment_id"=>$result['id'],
		"amount"=>$students_fees_src[0]["enrollee_discount"],
		"old_table_pk"=>$students_fees_src[0]["enrollee_id"]
	);
	
	$insert_discount = $dst->insertData($student_discount);

} else {

	$log = fopen("fees-items-logs.txt","a+");
	fwrite($log,"$result[id] has null old_table_pk");
	
}

$percent = ceil($c*100/$total);

echo "Imported $result[id] ($c/$total)...$percent% done\n";

$i++;

if ($i<$total) import($results[$i]);
else echo "$percent% done...\n";

}

?>