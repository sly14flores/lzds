<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$travel_orders = $con->getData("SELECT id, DATE_FORMAT(to_date,'%M %e, %Y') to_date, to_description, to_wholeday FROM travel_orders WHERE staff_id = $_POST[id]");

header("Content-type: application/json");
echo json_encode($travel_orders);

?>