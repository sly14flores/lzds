<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../classes.php';

session_start();

$con = new pdo_db();

$school_year = (isset($_POST['school_year']))?$_POST['school_year']:current_sy($con);

$sql = "SELECT id, school_year, category, description FROM fees WHERE school_year = ".$school_year['id']." ORDER BY school_year DESC, id ASC";

$fees = $con->getData($sql);
foreach ($fees as $key => $fee) {
	$school_year = $con->getData("SELECT id, school_year FROM school_years WHERE id = ".$fee['school_year']);
	$fees[$key]['school_year'] = $school_year[0];
}

echo json_encode($fees);

?>