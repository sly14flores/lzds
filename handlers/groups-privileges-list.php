<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("groups");

$groups = $con->all(array("id","group_name","group_description"));

header("Content-type: application/json");
echo json_encode($groups);

?>