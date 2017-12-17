<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../../db.php';

$con = new pdo_db("enrollments");

$data = array("id"=>$_POST['id'],"rfid"=>$_POST['rfid'],"update_log"=>"CURRENT_TIMESTAMP");

$update = $con->updateData($data,'id');

?>