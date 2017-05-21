<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, lrn, school_id, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname, contact_no, NULL level FROM students";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " AND id = ".$_POST['q']['id'];

$students = $con->getData($sql);

echo json_encode($students);

?>