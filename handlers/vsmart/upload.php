<?php

$filename = $_FILES['file']['name'];

$f_names = explode(".",$filename);

if ($f_names[1] != "csv") exit();

move_uploaded_file($_FILES['file']['tmp_name'],$filename);

?>