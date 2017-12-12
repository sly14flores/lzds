<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db();

$teachers = $con->getData("SELECT id, CONCAT(firstname, ' ', lastname) fullname FROM staffs WHERE field_of_work = 'Teaching'");

echo json_encode($teachers);

?>