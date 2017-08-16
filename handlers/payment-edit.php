<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$payments = $con->getData("SELECT id, enrollment_id, description, payment_month, amount, official_receipt, payment_date FROM payments WHERE enrollment_id = ".$_POST['enrollment_id']);

echo json_encode(array("enrollment_info"=>$enrollment_info,"payments"=>$payments));

?>