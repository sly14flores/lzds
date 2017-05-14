<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, description FROM fees";

$fees = $con->getData($sql);

echo json_encode($fees);

?>