<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

$con = new pdo_db();

echo json_encode(current_sy($con));

?>