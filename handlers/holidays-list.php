<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$holidays = $con->getData("SELECT id, DATE_FORMAT(holiday_date,'%M %e, %Y') holiday_date, holiday_description FROM holidays");

header("Content-type: application/json");
echo json_encode($holidays);

?>