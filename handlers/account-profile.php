<?php

$_POST = json_decode(file_get_contents('php://input'), true);

require_once '../db.php';
require_once '../system_privileges.php';
require_once '../privileges.php';

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

$con->table = "groups";
$group_privileges = $con->get(array("id"=>$_SESSION['group']),["privileges"]);

$pages_access = [];
if (count($group_privileges)) {
	if ($group_privileges[0]['privileges']!=NULL) {

		$privileges_obj = new privileges(system_privileges,$group_privileges[0]['privileges']);
		$pages_access = $privileges_obj->getPagesPrivileges();

	};
};

$profile['profile']['pages_access'] = $pages_access;

echo json_encode($profile);

?>