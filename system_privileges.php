<?php

define('system_privileges', array(
	array(
		"id"=>"dashboard",
		"description"=>"Dashboard",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Dashboard","value"=>false),
		),
	),
	array(
		"id"=>"students",
		"description"=>"Students",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Students","value"=>false),		
			array("id"=>2,"description"=>"Add New Student","value"=>false),
			array("id"=>3,"description"=>"View Student Info","value"=>false),
		),
	)
));

?>