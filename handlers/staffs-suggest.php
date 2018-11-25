<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, rfid, school_id, lastname, firstname, SUBSTR(middlename,1,1) mi, CONCAT(firstname, ' ', middlename, ' ', lastname) fullname, CONCAT(lastname, ', ', firstname, ' ', middlename) full_name, schedule_id, employment_status FROM staffs WHERE id > 1 AND (employment_status != 'EOC' AND employment_status != 'Resigned') ORDER BY lastname, firstname, middlename ASC";

$staffs = $con->getData($sql);

header("Content-type: application/json");
echo json_encode($staffs);

?>