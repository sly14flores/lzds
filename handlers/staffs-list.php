<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, school_id, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname, '' division, position, address FROM staffs WHERE id > 1";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " AND id = ".$_POST['q']['id'];

$staffs = $con->getData($sql);

echo json_encode($staffs);

?>