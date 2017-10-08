<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, description FROM schedules";

$schedules = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($schedules);

?>