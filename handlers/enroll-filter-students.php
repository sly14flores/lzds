<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$students = $con->getData("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) fullname, recent_sy, recent_level, recent_section FROM students");

echo json_encode($students);

?>