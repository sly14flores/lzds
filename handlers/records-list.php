<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("staffs_records");

$records = $con->get(array("staff_id"=>$_POST['id']),array("id","staff_id","record_type","record_subject","record_text"));

echo json_encode($records);

?>