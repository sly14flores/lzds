<?php

define('system_privileges', array(
	array(
		"id"=>"dashboard",
		"description"=>"Dashboard",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Dashboard","value"=>true),
		),
	),
	array(
		"id"=>"students",
		"description"=>"Students",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Students","value"=>false),		
			array("id"=>2,"description"=>"Add New Student","value"=>false),
			array("id"=>3,"description"=>"View Student Info","value"=>false),
			array("id"=>4,"description"=>"Edit Student Info","value"=>false),
			array("id"=>"delete_student","description"=>"Delete Student","value"=>false),
			array("id"=>5,"description"=>"Enroll Student","value"=>false),
			array("id"=>6,"description"=>"View Enrollment Info","value"=>false),
			array("id"=>7,"description"=>"Edit Enrollment Info","value"=>false),
			array("id"=>"delete_enrollment","description"=>"Delete Enrollment","value"=>false),
			array("id"=>8,"description"=>"Add Student Record","value"=>false),
			array("id"=>9,"description"=>"View Student Record Info","value"=>false),
			array("id"=>10,"description"=>"Update Student Record Info","value"=>false),
			array("id"=>"delete_record","description"=>"Delete Student Record","value"=>false),
			array("id"=>11,"description"=>"Add Student Excuse Letter","value"=>false),
			array("id"=>12,"description"=>"View Student Excuse Letter Info","value"=>false),
			array("id"=>13,"description"=>"Update Student Excuse Letter Info","value"=>false),
			array("id"=>"delete_excuse_letter","description"=>"Delete Student Excuse Letter","value"=>false),				
		),
	),
	array(
		"id"=>"staffs",
		"description"=>"Staffs",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Staffs","value"=>false),
			array("id"=>2,"description"=>"Add New Staff","value"=>false),
			array("id"=>3,"description"=>"View Staff Info","value"=>false),
			array("id"=>4,"description"=>"Edit Staff Info","value"=>false),
			array("id"=>"delete_staff","description"=>"Delete Staff","value"=>false),
			array("id"=>5,"description"=>"Add Leave","value"=>false),
			array("id"=>6,"description"=>"View Leave Info","value"=>false),
			array("id"=>7,"description"=>"Update Leave Info","value"=>false),
			array("id"=>"delete_leave","description"=>"Delete Leave","value"=>false),
			array("id"=>8,"description"=>"Add Travel Order","value"=>false),
			array("id"=>9,"description"=>"View Travel Order Info","value"=>false),
			array("id"=>10,"description"=>"Update Travel Order Info","value"=>false),
			array("id"=>"delete_to","description"=>"Delete Travel Order","value"=>false),
			array("id"=>11,"description"=>"Add Loan","value"=>false),
			array("id"=>12,"description"=>"View Loan Info","value"=>false),
			array("id"=>13,"description"=>"Update Loan Info","value"=>false),
			array("id"=>"view_loan_payments","description"=>"View Loan Payments","value"=>false),
			array("id"=>"delete_loan","description"=>"Delete Loan","value"=>false),
			array("id"=>14,"description"=>"Add Record","value"=>false),
			array("id"=>15,"description"=>"View Record Info","value"=>false),
			array("id"=>16,"description"=>"Update Record Info","value"=>false),
			array("id"=>17,"description"=>"Print Record","value"=>false),
			array("id"=>"delete_record","description"=>"Delete Record","value"=>false),
		),
	),
	array(
		"id"=>"school_year",
		"description"=>"School Year",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show School Year","value"=>false),
			array("id"=>2,"description"=>"Enroll Student","value"=>false),
			array("id"=>3,"description"=>"View Enrollment","value"=>false),
			array("id"=>4,"description"=>"Edit Enrollment","value"=>false),
			array("id"=>5,"description"=>"Print Enrollment","value"=>false),
			array("id"=>"delete_enrollment","description"=>"Delete Enrollment","value"=>false),			
		),
	),
	array(
		"id"=>"ids",
		"description"=>"Manage IDs",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Manage IDs","value"=>false),
			array("id"=>2,"description"=>"Generate IDs","value"=>false),
			array("id"=>3,"description"=>"Print IDs List","value"=>false),
		),
	),
	array(
		"id"=>"cashier",
		"description"=>"Cashier",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Cashier","value"=>false),
			array("id"=>2,"description"=>"Open Student Enrollment Payments","value"=>false),
			array("id"=>3,"description"=>"Add Payment","value"=>false),
			array("id"=>4,"description"=>"Update Payment Info","value"=>false),
			array("id"=>5,"description"=>"Generate SOA","value"=>false),
			array("id"=>6,"description"=>"Print Payments","value"=>false),
			array("id"=>7,"description"=>"Send SOA email","value"=>false),
			array("id"=>8,"description"=>"Send bulk SOA emails","value"=>false),
			array("id"=>"delete_payment","description"=>"Delete Payment","value"=>false),
		),
	),
	array(
		"id"=>"dtr_staffs",
		"description"=>"DTR (Staffs)",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show DTR (Staffs)","value"=>false),
			array("id"=>2,"description"=>"Import DTR","value"=>false),
			array("id"=>3,"description"=>"View DTR","value"=>false),
			array("id"=>4,"description"=>"Re-analyze DTR","value"=>false),
			array("id"=>5,"description"=>"View Logs (per day)","value"=>false),
			array("id"=>6,"description"=>"Manage Logs (per day)","value"=>false),
		),
	),
	array(
		"id"=>"dtr_students",
		"description"=>"DTR (Students)",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show DTR (Students)","value"=>false),
			array("id"=>2,"description"=>"Import DTR","value"=>false),
			array("id"=>3,"description"=>"View DTR","value"=>false),
			array("id"=>4,"description"=>"Re-analyze DTR","value"=>false),
			array("id"=>5,"description"=>"View Logs (per day)","value"=>false),
			array("id"=>6,"description"=>"Manage Logs (per day)","value"=>false),			
		),
	),
	array(
		"id"=>"payroll",
		"description"=>"Payroll",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Payroll","value"=>false),
			array("id"=>2,"description"=>"Generate Individual Payroll","value"=>false),
			array("id"=>3,"description"=>"Re-process Individual Payroll","value"=>false),
			array("id"=>4,"description"=>"Update Payroll Info","value"=>false),
			array("id"=>5,"description"=>"Print Individual Payroll","value"=>false),
			array("id"=>6,"description"=>"Print Payroll Sheet","value"=>false),
		),
	),
	array(
		"id"=>"balances_reports",
		"description"=>"Balances Reports",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Balances Reports","value"=>false),
			array("id"=>2,"description"=>"Generate Report","value"=>false),
		),
	),
	array(
		"id"=>"summary_reports",
		"description"=>"Summary Reports",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Summary Reports","value"=>false),
			array("id"=>2,"description"=>"Generate Report","value"=>false),
		),
	),
	array(
		"id"=>"levels",
		"description"=>"Grade Levels",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Grade Levels","value"=>false),
			array("id"=>2,"description"=>"Add New Level","value"=>false),
			array("id"=>3,"description"=>"View Level","value"=>false),
			array("id"=>4,"description"=>"Update Level Info","value"=>false),
			array("id"=>"delete_level","description"=>"Delete Level","value"=>false),
		),
	),
	array(
		"id"=>"school_fees",
		"description"=>"School Fees",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show School Fees","value"=>false),
			array("id"=>2,"description"=>"Add New Fee","value"=>false),
			array("id"=>3,"description"=>"View Fee Info","value"=>false),
			array("id"=>4,"description"=>"Edit Fee Info","value"=>false),
			array("id"=>5,"description"=>"Clone Fee","value"=>false),
			array("id"=>"delete_fee","description"=>"Delete Fee","value"=>false),
		),
	),
	array(
		"id"=>"schedules",
		"description"=>"Schedules",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Schedules","value"=>false),
			array("id"=>2,"description"=>"Add New Schedule","value"=>false),
			array("id"=>3,"description"=>"View Schedule Info","value"=>false),
			array("id"=>4,"description"=>"Edit Schedule Info","value"=>false),
			array("id"=>"delete_schedule","description"=>"Delete Schedule","value"=>false),
		),
	),
	array(
		"id"=>"holidays",
		"description"=>"Holidays",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Holidays","value"=>false),
			array("id"=>2,"description"=>"Add New Holiday","value"=>false),
			array("id"=>3,"description"=>"View Holiday Info","value"=>false),
			array("id"=>4,"description"=>"Update Holiday Info","value"=>false),
			array("id"=>"delete_holiday","description"=>"Delete Holiday","value"=>false),
		),
	),
	array(
		"id"=>"groups",
		"description"=>"Groups",
		"privileges"=>array( # id=1 must be always page access
			array("id"=>1,"description"=>"Show Groups","value"=>false),
			array("id"=>2,"description"=>"Add New Group","value"=>false),
			array("id"=>3,"description"=>"View Group Info","value"=>false),
			array("id"=>4,"description"=>"Edit Group Info","value"=>false),
			array("id"=>"delete_group","description"=>"Delete Group","value"=>false),
		),
	),
));

?>