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
			array("id"=>"delete_student","description"=>"Delete Student","value"=>false),
			array("id"=>4,"description"=>"Enroll Student","value"=>false),
			array("id"=>5,"description"=>"View Enrollment Info","value"=>false),
			array("id"=>"delete_enrollment","description"=>"Delete Enrollment","value"=>false),
		),
	),
	array(
		"id"=>"staffs",
		"description"=>"Staffs",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Staffs","value"=>false),
		),
	),
	array(
		"id"=>"school_year",
		"description"=>"School Year",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show School Year","value"=>false),
		),
	),
	array(
		"id"=>"cashier",
		"description"=>"Cashier",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Cashier","value"=>false),
		),
	),
	array(
		"id"=>"dtr_staffs",
		"description"=>"DTR (Staffs)",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show DTR (Staffs)","value"=>false),
		),
	),
	array(
		"id"=>"dtr_students",
		"description"=>"DTR (Students)",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show DTR (Students)","value"=>false),
		),
	),
	array(
		"id"=>"payroll",
		"description"=>"Payroll",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Payroll","value"=>false),
		),
	),
	array(
		"id"=>"balances_reports",
		"description"=>"Balances Reports",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Balances Reports","value"=>false),
		),
	),
	array(
		"id"=>"summary_reports",
		"description"=>"Balances Reports",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Summary Reports","value"=>false),
		),
	),
	array(
		"id"=>"levels",
		"description"=>"Grade Levels",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Grade Levels","value"=>false),
		),
	),
	array(
		"id"=>"school_fees",
		"description"=>"School Fees",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show School Fees","value"=>false),
		),
	),
	array(
		"id"=>"schedules",
		"description"=>"Schedules",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Schedules","value"=>false),
		),
	),
	array(
		"id"=>"holidays",
		"description"=>"Holidays",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Holidays","value"=>false),
		),
	),
	array(
		"id"=>"groups",
		"description"=>"Groups",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Groups","value"=>false),
		),
	),
));

?>