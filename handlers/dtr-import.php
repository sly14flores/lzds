<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

header("Content-type: application/json");

?>