<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname FROM staffs WHERE id > 1";

$staffs = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($staffs);

?>