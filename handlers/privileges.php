<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../system_privileges.php';
require_once '../privileges.php';

$con = new pdo_db("groups");

$group_privileges = $con->get(array("id"=>$_POST['id']),["privileges"]);

if (count($group_privileges)) {
	
	if ($group_privileges[0]['privileges']!=NULL) {

		$privileges_obj = new privileges(system_privileges,$group_privileges[0]['privileges']);
		$privileges = $privileges_obj->getPrivileges();

	} else {
		
		$privileges = system_privileges;		
		
	}

} else {
	
	$privileges = system_privileges;	
	
}

echo json_encode($privileges);

?>