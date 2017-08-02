<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$sql = "SELECT id, school_year, category, description FROM fees ORDER BY school_year DESC, id ASC";
if ( (isset($_POST['q'])) && ($_POST['q'] != "") ) $sql .= " WHERE id = ".$_POST['q']['id'];

$fees = $con->getData($sql);
foreach ($fees as $key => $fee) {
	$school_year = $con->getData("SELECT id, school_year FROM school_years WHERE id = ".$fee['school_year']);
	$fees[$key]['school_year'] = $school_year[0];
}

echo json_encode($fees);

?>