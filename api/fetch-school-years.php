<?php

header("content-type: application/json; charset=utf-8");
header("access-control-allow-origin: *");

require_once '../db.php';

$con = new pdo_db("school_years");

$sql = "SELECT id, school_year FROM school_years";
$school_years = $con->getData($sql);

echo json_encode($school_years);

?>