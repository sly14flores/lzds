<?php

require_once 'system_privileges.php';

function toHex($string){
	$string = json_encode($string);
	$hex = '';
	for ($i=0; $i<strlen($string); $i++){
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0'.$hexCode, -2);
	}
	return strToUpper($hex);
};

function toArray($hex){
	$string='';
	for ($i=0; $i < strlen($hex)-1; $i+=2){
		$string .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return json_decode($string,true);
};

$system_privileges = system_privileges;

foreach ($system_privileges as $i => $sp) {

	foreach ($sp['privileges'] as $key => $privilege) {

		$system_privileges[$i]['privileges'][$key]['value'] = true;

	};
	
};

$system_privileges = toHex(json_encode($system_privileges));

echo '<textarea style="width: 50%; height: 100%;">'.$system_privileges.'</textarea>';

?>