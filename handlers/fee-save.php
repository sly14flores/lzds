<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';

session_start();

$con = new pdo_db("fees");

if ($_POST['fee']['id']) { // > 0 - update
	$_POST['fee']['school_year'] = $_POST['fee']['school_year']['id'];
	if (gettype($_POST['fee']['category']) == "array") $_POST['fee']['category'] = $_POST['fee']['category']['category'];
	$fee = $con->updateData($_POST['fee'],'id');
	$fee_id = $_POST['fee']['id'];
} else { // 0 - insert
	unset($_POST['fee']['id']);
	$_POST['fee']['school_year'] = $_POST['fee']['school_year']['id'];
	if (gettype($_POST['fee']['category']) == "array") $_POST['fee']['category'] = $_POST['fee']['category']['category'];	
	$_POST['fee']['update_log'] = "CURRENT_TIMESTAMP";
	$fee = $con->insertData($_POST['fee']);
	$fee_id = $con->insertId;
}

if (count($_POST['fee_items'])) {
	
	$con->table = "fee_items";
	
	foreach ($_POST['fee_items'] as $key => $item) {
		
		unset($_POST['fee_items'][$key]['disabled']);
		$_POST['fee_items'][$key]['level'] = $_POST['fee_items'][$key]['level']['id'];
		
		if ($item['id'] > 0) {
			$fee_item = $con->updateData($_POST['fee_items'][$key],'id');
		} else {
			$_POST['fee_items'][$key]['update_log'] = "CURRENT_TIMESTAMP";
			unset($_POST['fee_items'][$key]['id']);
			$_POST['fee_items'][$key]['fee_id'] = $fee_id;
			$fee_item = $con->insertData($_POST['fee_items'][$key]);			
		}
		
	}
	
}

if (count($_POST['fee_items_del'])) {
	
	$con->table = "fee_items";
	$delete = $con->deleteData(array("id"=>implode(",",$_POST['fee_items_del'])));	
	
}

?>