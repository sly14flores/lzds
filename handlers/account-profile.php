<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db();

$profile = [];
$sql = "SELECT school_id FROM staffs WHERE id = $_SESSION[id]";
$staff = $con->getData($sql);

$profile['profile']['staff'] = $_SESSION['staff'];

$dir = "pictures/";
$avatar = $dir."avatar.png";

$picture = $dir.$staff[0]['school_id'].".jpg";
if (!file_exists("../".$picture)) $picture = $avatar;

$profile['profile']['picture'] = $picture;

echo json_encode($profile);

?>