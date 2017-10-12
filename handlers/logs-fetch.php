<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db2.php';

$con = new pdo_db("monitoring","attendances");

$filter = " WHERE time_log LIKE '".$_POST['year']."-".$_POST['month']['month']."%'";

$sql = "SELECT * FROM attendances$filter";
$logs = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($logs);

?>