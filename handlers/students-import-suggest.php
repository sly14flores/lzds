<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db2.php';

session_start();

$con = new pdo_db("lzds");

$sql = "SELECT CONCAT(enrollee_lname, ' ', enrollee_fname, ' ', enrollee_mname) fullname FROM enrollees";

$students = $con->getData($sql);

echo json_encode($students);

?>