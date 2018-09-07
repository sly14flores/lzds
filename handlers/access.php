<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../system_privileges.php';
require_once '../privileges.php';

$con = new pdo_db("groups");

session_start();

$group_privileges = $con->get(array("id"=>$_SESSION['group']),["privileges"]);

$access = array("value"=>false);

if (count($group_privileges)) {
	if ($group_privileges[0]['privileges']!=NULL) {

		$privileges_obj = new privileges(system_privileges,$group_privileges[0]['privileges']);
		$access = array("value"=>$privileges_obj->hasAccess($_POST['mod'],$_POST['prop']));

	};
};

echo json_encode($access);

?>