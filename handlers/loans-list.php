<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$loans = $con->getData("SELECT id, DATE_FORMAT(loan_date,'%M %e, %Y') loan_date, loan_type, loan_description, loan_amount, loan_offset, loan_months, loan_monthly, loan_monthly_first, loan_monthly_second, DATE_FORMAT(loan_effectivity,'%M %e, %Y') loan_effectivity FROM loans WHERE staff_id = $_POST[id]");

header("Content-type: application/json");
echo json_encode($loans);

?>