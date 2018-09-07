<?php

$_POST = json_decode(file_get_contents('php://input'), true);

$subject = 'Lord of Zion Divine School - Billing Statement';
$to = $_POST['email_address'];
$message = $_POST['message'];
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: Lord of Zion Divine School <lordofziondivineschool@gmail.com>' . "\r\n";

$parent_emailed = mail($to, $subject, $message, $headers);

$response = array("status"=>$parent_emailed);

echo json_encode($response);

?>