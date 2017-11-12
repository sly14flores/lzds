<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, rfid, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname, schedule_id, employment_status FROM staffs WHERE id > 1";

$staffs = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($staffs);

?>