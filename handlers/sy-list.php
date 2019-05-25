<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$school_years = $con->getData("SELECT id, school_year FROM school_years");

header("Content-type: application/json");
echo json_encode($school_years);

?>