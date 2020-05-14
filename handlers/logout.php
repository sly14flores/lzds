<?php

session_start();

if (isset($_SESSION['id'])) unset($_SESSION['id']);
if (isset($_SESSION['staff'])) unset($_SESSION['staff']);

// echo "Logout Successful";

header("location: ../index.php");

?>