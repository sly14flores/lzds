<?php

require_once '../db2.php';

$src = new pdo_db("lzds");
$dst = new pdo_db("lzdssystem","enrollments");

// $start = 1;
// $end = 1789;

// $results = $dst->getData("SELECT * FROM enrollments WHERE id BETWEEN $start AND $end");
$results = $dst->getData("SELECT * FROM enrollments");

$i = 0;
$total = count($results);

import($results[$i]);

function import($result) {

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

global $src, $dst, $results, $total, $i;

$c = $i+1;

if ($result['old_table_pk'] != null) {

	$sql = "SELECT enrollee_section FROM enrollees WHERE enrollee_id = ".$result['old_table_pk'];
	$es = $src->getData($sql);

	if (count($es)) {
		
		$enrollee_section = array(
			"id"=>$result['id'],
			"section"=>(isset($enrollee_section[$es[0]['enrollee_section']]))?$_enrollee_section[$enrollee_section[$es[0]['enrollee_section']]]:0
		);

		$update = $dst->updateData($enrollee_section,'id');
		
	}
	
} else {
	
	$log = fopen("sections-fix-logs.txt","a+");
	fwrite($log,"$result[id] has null old_table_pk");
	
}

$percent = ceil($c*100/$total);

echo "Imported $result[id] ($c/$total)...$percent% done\n";

$i++;

if ($i<$total) import($results[$i]);
else echo "$percent% done...\n";

}

?>