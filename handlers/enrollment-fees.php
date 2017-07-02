<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$fees = $con->getData("SELECT fee_items.id, fees.description, fee_items.amount FROM fees LEFT JOIN fee_items ON fees.id = fee_items.fee_id WHERE fees.school_year = '".$_POST['school_year']['id']."' AND fee_items.level = ".$_POST['level']['id']);

echo json_encode($fees);

?>