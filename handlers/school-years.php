<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, school_year FROM school_years";
$school_years = $con->getData($sql);
$sql = "SELECT DISTINCT category FROM fees";
$categories = $con->getData($sql);

echo json_encode(array("school_years"=>$school_years,"categories"=>$categories));

?>