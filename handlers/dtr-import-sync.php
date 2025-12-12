<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

$con = new pdo_db("attendances");

$dtrs = $_POST['dtrs'];

foreach ($dtrs as $dtr) {

    $data = array(
        "attendance_id"=>$dtr['id'],
        "rfid"=>$dtr['rfid'],
        "time_log"=>$dtr['time_log'],
        "system_log"=>"CURRENT_TIMESTAMP"
    );

    $query = $con->getData("SELECT * FROM attendances WHERE attendance_id = ".$dtr['id']);

    if (count($query) == 0) {
        $attendance = $con->insertData($data);
        echo "Imported";
    } else {
        echo "Updated";
    }

}

?>