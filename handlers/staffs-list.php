<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT school_id, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname FROM staffs";
$staffs = $con->getData($sql);

echo json_encode($staffs);

?>