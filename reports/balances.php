<?php

$params = json_decode($_POST['params'],true);

$filter = $params;

require('../fpdf181/fpdf.php');
require('../db.php');

$con = new pdo_db();

$sy = $params['school_year']['school_year'];
$level = "";
$section = "All";

$wheres = [];
if ($filter['school_year']['id']) {
	$wheres[] = array("field"=>"enrollment_school_year","value"=>$filter['school_year']['id']);
}
if ($filter['level']['id']) {
	$wheres[] = array("field"=>"grade","value"=>$filter['level']['id']);	
	$levels = $con->getData("SELECT description FROM grade_levels WHERE id = ".$filter['level']['id']);	
	$level = $levels[0]['description'];
}
if ($filter['section']['id']) {
	$wheres[] = array("field"=>"section","value"=>$filter['section']['id']);	
	$sections = $con->getData("SELECT description FROM sections WHERE id = ".$filter['section']['id']);	
	$section = $sections[0]['description'];	
}

$where = "";
foreach($wheres as $i => $w) {
	if ($i) $where.=" AND ".$w['field']." = ".$w['value'];
	else $where.=" WHERE ".$w['field']." = ".$w['value'];
}

$order = " ORDER BY students.gender DESC, lastname, firstname, middlename";

$sql = "SELECT enrollments.id, enrollments.school_id, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, students.gender, (SELECT students.home_address FROM students WHERE students.id = enrollments.student_id) address, (SELECT students.contact_no FROM students WHERE students.id = enrollments.student_id) contact_no FROM enrollments LEFT JOIN students ON enrollments.student_id = students.id".$where.$order;
$enrollments = $con->getData($sql);

$total = 0;
foreach($enrollments as $key => $enrollment) {
	
	$student_fees = $con->getData("SELECT SUM(amount) student_fees FROM students_fees WHERE enrollment_id = ".$enrollment['id']);
	$student_discount = $con->getData("SELECT amount student_discount FROM students_discounts WHERE enrollment_id = ".$enrollment['id']);
	$student_payments = $con->getData("SELECT SUM(amount) student_payments FROM payments WHERE enrollment_id = ".$enrollment['id']);

	$balance = $student_fees[0]['student_fees'] - $student_discount[0]['student_discount'] - $student_payments[0]['student_payments'];
	$enrollments[$key]['balance'] = "Php. ".number_format(round($balance,2));
	$total += $balance;
	
	unset($enrollments[$key]['id']);
	
	$enrollments[$key]['fullname'] = iconv('UTF-8', 'ISO-8859-1', $enrollment['fullname']);
	
};

class PDF extends FPDF {

	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation);
			return true;
		}		
		return false;		
	}

	function NbLines($w,$txt) {
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0) $w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n") $nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb) {
			$c=$s[$i];
			if($c=="\n") {
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ') $sep=$i;
			$l+=$cw[$c];
			if($l>$wmax) {
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}	
	
	// Page header
	function Header() {

		global $top_margin, $header; # array-multi

		$this->Ln($top_margin);
		
		foreach ($header as $h) {
			$h($this);
		};	

	}

	// Page footer
	function Footer() {
		global $footer;
		
		foreach ($footer as $f) {
			$f($this);
		};

		// Position at 1.5 cm from bottom	
		$this->SetY(-10);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->SetTextColor(66,66,66);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'L');
	}
	
	function body($body) {
		
		$body['start']($this); # start
		$lr_margin = $body['lr_margin'];
		$body_top_margin = $body['top_margin'];		
		$headers = $body['headers'];
		$data = $body['data'];
		
		// Calculate the height of the header
		$nb=0;
		foreach ($headers as $h) {
			$nb=max($nb,$this->NbLines($h['width'],$h['column']));
		}
		$hh=5*$nb;
		
		# Header
		$this->SetXY($lr_margin,$body_top_margin);
		foreach ($headers as $i => $h) {
			$header_x = $this->GetX();			
			$header_y = $this->GetY();
			$this->Rect($header_x,$header_y,$h['width'],$hh,'DF');
			$this->MultiCell($h['width'],5,$h['column'],0,'C');
			$this->SetXY($header_x+$h['width'],$header_y);			
		}
		$this->Ln($hh);
		# end Header		
		
		$fill = false;
		$body['striped_bg']($this);
		foreach($data as $key => $row) {
			
			$this->SetX($lr_margin);
			
			// Calculate the height of each body row column
			$nb=0;
			foreach ($headers as $i => $h) {
				$nb=max($nb,$this->NbLines($h['width'],$row[array_keys($row)[$i]]));
			}
			$rh=5*$nb;

			$pageBreak = $this->CheckPageBreak($rh);

			if ($pageBreak) { # if page break render header
				if ($this->PageNo()>1) {
					$this->SetXY($lr_margin,$body_top_margin);
				}
				$this->SetFillColor(227,227,227); # background color
				# Header
				foreach ($headers as $i => $h) {
					$header_x = $this->GetX();			
					$header_y = $this->GetY();
					$this->Rect($header_x,$header_y,$h['width'],$hh,'DF');
					$this->MultiCell($h['width'],5,$h['column'],0,'C');
					$this->SetXY($header_x+$h['width'],$header_y);
				}
				$this->Ln($hh);
				# end Header
				
				$this->SetX($lr_margin);
				
			}
			
			$this->SetFillColor(223,223,223);		
			foreach ($headers as $i => $h) {
				$body_x = $this->GetX();					
				$body_y = $this->GetY();
				$df = 'D';
				if ($body['striped']) if ($fill) $df = 'DF';
				$this->Rect($body_x,$body_y,$h['width'],$rh,$df);
				$content = iconv('UTF-8', 'ISO-8859-1', $row[array_keys($row)[$i]]);
				// $content = utf8_encode($row[array_keys($row)[$i]]);
				$this->MultiCell($h['width'],5,$content,0,'C');
				$this->SetXY($body_x+$h['width'],$body_y);
			}
			$this->Ln($rh);				
			$fill = !$fill;

		}
		
		$this->Ln();	
		$this->SetX(20);
		global $total;
		$this->SetFont('Arial','B',14);
		$this->Cell(240,10,"Total: Php.".number_format(round($total,2)),0,1,'R');

	}
	
	function darkText() {
		$this->SetTextColor(96,125,139);
	}
	
	function darkerText() {
		$this->SetTextColor(69,90,100);	
	}
	
}
# start
$top_margin = 5;

$header = array(
	function($p) {
		$p->Image("../img/lzds-logo-gray.png",235,25,25);	
	},
	function($p) {
		echo null; # important in include
		global $sy, $level, $section;
		$p->SetFont('Arial','B',14);	
		$p->SetXY(18,15);
		$p->darkerText();
		$p->Cell(0,5,"Lord of Zion Divine School",0,1,'L');
		$p->SetFont('Arial','B',12);		
		$p->SetXY(160,15);
		$p->Cell(100,5,"Balances Report",0,1,'R');	
		$p->SetFont('Arial','',10);
		$p->darkText();
		$p->SetXY(18,25);
		$p->Cell(0,5,"Paratong, Bacnotan, La Union, 2515",0,1,'L');
		$p->SetXY(18,30);
		$p->Cell(0,5,"Tel. No.: (072) 607 4004",0,1,'L');
		$p->darkerText();
		$p->SetXY(18,38);
		$p->Cell(0,5,"SY: $sy",0,1,'L');
		$p->SetXY(18,43);
		$p->Cell(0,5,"Level: $level",0,1,'L');
		$p->SetX(18);
		$p->Cell(0,5,"Section: $section",0,1,'L');	
		
	}
);
$footer = array(
	function($p) {
		echo null; # important in include
	}
);
$headers = array(
	array("width"=>15,"column"=>"ID"),
	array("width"=>25,"column"=>"Grade"),
	array("width"=>25,"column"=>"Section"),
	array("width"=>50,"column"=>"Name"),
	array("width"=>15,"column"=>"Gender"),
	array("width"=>50,"column"=>"Address"),
	array("width"=>30,"column"=>"Contact No"),
	array("width"=>30,"column"=>"Balance")
);

$data = $enrollments;

$body = array(
	"lr_margin"=>20,
	"top_margin"=>60,	
	"striped"=>false,
	"striped_bg"=>function($p) {
		$p->SetFillColor(223,223,223); # background color		
	},
	"headers"=>$headers,
	"data"=>$data,
	"start"=>function($p) {
		echo null; # important in include
		// Colors, line width and bold font
		$p->SetDrawColor(92,92,92); # border color
		$p->SetFillColor(227,227,227); # background color
		$p->SetTextColor(66,66,66); # font color
		$p->SetLineWidth(.1); # border width
		$p->SetFont('Arial','B',8);
	}
);
# end
$pdf = new PDF('L','mm','Letter'); # set here if Portrait(P)/Landscape(L)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->body($body);
$pdf->Output();

?>