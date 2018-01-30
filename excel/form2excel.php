<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once 'PHPExcel/IOFactory.php';

$date = $_POST['year']."-".$_POST['month']['month']."-01";

$fileName = 'SF_2_Daily_Attendance.xlsx';
$output = "form2excel.xlsx";
$school_id = "400075";
$school = "Lord of Zion Divine School";
$sy = $_POST['sy']['school_year'];
$month = $_POST['month']['description'];

// Read the file
$fileType = PHPExcel_IOFactory::identify($fileName);
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);

// Add entries
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 6, $school_id);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 8, $school);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(10, 6, $sy);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(23, 6, $month);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(23, 8, $_POST['grade']['description']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(28, 8, $_POST['section']['description']);

$start = date("Y-m-d",strtotime($date));
$end = date("Y-m-t",strtotime($date));

$weekdays = [];
while (strtotime($start) <= strtotime($end)) {

if ( (date("D",strtotime($start)) != "Sat") && (date("D",strtotime($start)) != "Sun") ) {
	if (date("D",strtotime($start)) == "Thu") {
		$weekdays[] = array("date"=>$start,substr(date("D",strtotime($start)),0,2)=>date("j",strtotime($start)));
	} else {
		$weekdays[] = array("date"=>$start,substr(date("D",strtotime($start)),0,1)=>date("j",strtotime($start)));
	}
}

$start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
	
}

$c = 3;
$colLetters = ["D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB"];

$markAbsent = array(
    'borders' => array(
        'diagonal' => array(
            // 'style' => PHPExcel_Style_Border::BORDER_THICK,
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
        'diagonaldirection' => PHPExcel_Style_Borders::DIAGONAL_BOTH
    )
);

$markHalfday = array(
    'borders' => array(
        'diagonal' => array(
            // 'style' => PHPExcel_Style_Border::BORDER_THICK,
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
        'diagonaldirection' => PHPExcel_Style_Borders::DIAGONAL_UP
    )
);

foreach ($weekdays as $day) {
	foreach ($day as $key => $value) {
		if ($key == "date") continue;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($c, 11, $value);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($c, 12, $key);
	}
	$c++;
}

$boysStart = 13;
$girlsStart = 35;

$con = new pdo_db();
$sql = "SELECT enrollments.id, enrollments.student_id, enrollments.rfid, CONCAT(students.lastname, ', ', students.firstname, ' ', SUBSTRING(students.lastname,1,1)) fullname, students.gender FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id WHERE enrollments.enrollment_school_year = ".$_POST['sy']['id']." AND grade = ".$_POST['grade']['id']." AND section = ".$_POST['section']['id']." ORDER BY students.gender DESC, students.lastname ASC, students.firstname ASC";
$students = $con->getData($sql);

# Male

$n = 1;
foreach ($students as $key => $student) {
	
	if ($student['gender'] == "Female") continue;	
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $boysStart, $n);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $boysStart, $student['fullname']);
	
	$col = $boysStart;
	
	foreach ($weekdays as $i => $day) {
		
		# if Sat/Sun skip
		if ( (date("D",strtotime($day['date']))=="Sat") || (date("D",strtotime($day['date']))=="Sun") ) continue;
		
		$cell = $colLetters[$i].$col;
		
		$dtr = $con->getData("SELECT * FROM dtr_students WHERE ddate = '".$day['date']."' AND rfid = '".$student['rfid']."'");
		
		foreach ($dtr as $d) {
		
			if ($d['absent']) {
				$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($markAbsent);			
			};
			
			if ($d['is_halfday']) {
				$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($markHalfday);			
			};

		};

	};
	
	$boysStart++;
	$n++;
	
};

# Female

$n = 1;
foreach ($students as $key => $student) {
	
	if ($student['gender'] == "Male") continue;
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(0, $girlsStart, $n);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $girlsStart, $student['fullname']);
	
	$col = $girlsStart;
	
	foreach ($weekdays as $i => $day) {
		
		# if Sat/Sun skip
		if ( (date("D",strtotime($day['date']))=="Sat") || (date("D",strtotime($day['date']))=="Sun") ) continue;
		
		$cell = $colLetters[$i].$col;
		
		$dtr = $con->getData("SELECT * FROM dtr_students WHERE ddate = '".$day['date']."' AND rfid = '".$student['rfid']."'");
		
		foreach ($dtr as $d) {
		
			if ($d['absent']) {
				$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($markAbsent);			
			};
			
			if ($d['is_halfday']) {
				$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($markHalfday);			
			};

		};

	};
	
	$girlsStart++;
	$n++;
	
};

$objPHPExcel->getActiveSheet()->getRowDimension('63')->setRowHeight(22);

// Write the file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

$dir = "../reports";

copy($output,"$dir/$output");

$final_name = "$dir/SF_2_Daily_Attendance_".$_POST['grade']['description']."_".$_POST['section']['description']."_".$month."_".$_POST['year'].".xlsx";
rename("$dir/$output",$final_name);

echo "lzds/$final_name";

?>