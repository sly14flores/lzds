<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("students_records");

$records = $con->getObj(array("student_id"=>$_POST['id']),array("id","student_id",array("record_sy"=>array("school_years"=>["id","school_year"])),"record_description","record_subject","record_text"));

echo json_encode($records);

?>