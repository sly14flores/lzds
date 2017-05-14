<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$fee = $con->getData("SELECT * FROM fees WHERE id = $_POST[id]");

unset($fee[0]['system_log']);
unset($fee[0]['update_log']);

echo json_encode($fee[0]);

?>