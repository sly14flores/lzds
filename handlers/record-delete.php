<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("staffs_records");

$delete = $con->deleteData(array("id"=>implode(",",$_POST['id'])));	

?>