<?php

require_once '../../db.php';
require_once '../excel/PHPExcel.php';

$_POST = json_decode($_POST['form2excel'], true);

$file = "SF_2_Daily_Attendance.xlsx";
$filename = "student_attendance";

$defboysRows = 21;
$boysLastRow = 33;
$addedBoysRows = 0;

$defgirlsRows = 25;
$girlsLastRow = 59;

$objPHPExcel = PHPExcel_IOFactory::load($file);

/* $objPHPExcel->getProperties()->setCreator("Attendance Monitoring System")
							 ->setLastModifiedBy("Attendance Monitoring System")
							 ->setTitle("Attendance Report")
							 ->setSubject("Attendance Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("Reports"); */
							 
// $objWorksheet = $objPHPExcel->getActiveSheet();

/*
** additional rows for boys
*/
/* $addBoysRows = 2;
for ($i=1; $i<=$addBoysRows; ++$i) {
	$objWorksheet->insertNewRowBefore($boysLastRow, 1);	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$boysLastRow.':C'.$boysLastRow);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE'.$boysLastRow.':AJ'.$boysLastRow);
	$addedBoysRows++;
} */

/*
** additional rows for girls
*/
/* $girlsLastRow+=$addedBoysRows;
$addGirlsRows = 2;
for ($i=1; $i<=$addGirlsRows; ++$i) {
	$objWorksheet->insertNewRowBefore($girlsLastRow, 1);	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$girlsLastRow.':C'.$girlsLastRow);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE'.$girlsLastRow.':AJ'.$girlsLastRow);
} */

// $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1","");
// $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col, $row, $value); // col index starts at 0
// $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(2, 8, "Lord of Zion Divine School");

// $objPHPExcel->setActiveSheetIndex(0); 

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>