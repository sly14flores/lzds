<?php

$csv = "enrollmenthistory.csv";

echo json_encode(array("exists"=>file_exists($csv)));

?>