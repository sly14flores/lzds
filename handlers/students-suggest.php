<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) fullname FROM students";

$students = $con->getData($sql);

echo json_encode($students);

?>