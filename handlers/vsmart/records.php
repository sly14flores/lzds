<?php

class Records {
	
	var $data;
	var $records;
	var $csv;

	function __construct($csv) {

		$this->csv = $csv;
		$this->data = fopen($csv,"r");
		
	}
	
	public function getRecordsCount() {
		
		$rows = count(file($this->csv))-1;
		
		return $rows;
		
	}
	
	public function getRecords() {
		
		$this->records = [];
		
		while(! feof($this->data)) {
			
			$record = fgetcsv($this->data);
			
			if (gettype($record)=="array") $this->records[] = $record;
			
		}
		
		$this->close();
		
		return $this->records;
		
	}
	
	public function getRecord($i) {
		
	}
	
	public function close() {
		
		fclose($this->data);
		
	}
	
}

$_POST = json_decode(file_get_contents('php://input'), true);

$csv = "enrollmenthistory.csv";
$records = new Records($csv);

$message = '<p>';

switch ($_POST['action']) {

	case "count":

		$message .= "Found ".$records->getRecordsCount()." records";
	
	break;
	
	case "records":
	
		echo json_encode($records->getRecords());
		exit();
	
	break;	
	
	case "analyze":
	
		$message .= "Analyzing ".$_POST['record'][0]." data";
		$message .= '<ul id="record-'.$_POST['i'].'"></ul>';
	
	break;
	
}

$message .= '</p>';

echo $message;

?>