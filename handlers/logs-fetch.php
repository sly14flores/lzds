<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db2.php';

$con = new pdo_db("monitoring","attendances");

$filter = " AND time_log LIKE '".$_POST['year']."-".$_POST['month']['month']."%'";

$sql = "SELECT attendances.id, attendances.rfid, attendances.time_log FROM attendances LEFT JOIN profiles ON attendances.rfid = profiles.rfid WHERE profile_type = 'Staff'$filter";
$logs = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($logs);

?>