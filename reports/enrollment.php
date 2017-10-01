<?php

$params = json_decode($_POST['params'],true);

$filter = $params['filter'];

require('../fpdf181/fpdf.php');
require('../db.php');

$con = new pdo_db();

$enrollment = $con->getData("SELECT (SELECT CONCAT(students.lastname, ', ', students.firstname, ' ', students.middlename) FROM students WHERE students.id = enrollments.student_id) fullname, (SELECT students.lrn from students WHERE students.id = enrollments.student_id) lrn, (SELECT students.home_address FROM students WHERE students.id = enrollments.student_id) address, (SELECT grade_levels.description FROM grade_levels WHERE grade_levels.id = enrollments.grade) grade, (SELECT sections.description FROM sections WHERE sections.id = enrollments.section) section, (SELECT school_years.school_year FROM school_years WHERE school_years.id = enrollments.enrollment_school_year) school_year, (SELECT students_discounts.amount FROM students_discounts WHERE students_discounts.enrollment_id = enrollments.id) discount, enrollments.enrollment_date FROM enrollments WHERE id = ".$filter['id']);
$_fees = $con->getData("SELECT (SELECT fees.description FROM fees WHERE fees.id = (SELECT fee_items.fee_id FROM fee_items WHERE fee_items.id = students_fees.fee_item_id)) description, students_fees.amount amount FROM students_fees WHERE students_fees.enrollment_id = ".$filter['id']);
$fees = [];
$sub_total = 0;
$total = 0;
foreach ($_fees as $_fee) {
	$fees[] = $_fee;
	$sub_total += $_fee['amount'];
}
$fees[] = array("description"=>"Total","amount"=>$sub_total);

$total = $sub_total-$enrollment[0]['discount'];

class PDF extends FPDF {
	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation);		
		}
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
		
		/* // Position at 1.5 cm from bottom	
		$this->SetY(-10);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->SetTextColor(66,66,66);	
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'L'); */
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
		
		$spacer = "  ";
		
		# Header
		$hln = 7;
		$this->SetXY($lr_margin,$body_top_margin);
		$this->SetMargins($lr_margin,0);
		foreach ($headers as $i => $h) {
			$header_x = $this->GetX();			
			$header_y = $this->GetY();
			$this->Rect($header_x,$header_y,$h['width'],$hln,'DF');
			$this->MultiCell($h['width'],$hln,$spacer.$h['column'],0,'L');
			$this->SetXY($header_x+$h['width'],$header_y);
		}		
		$this->Ln($hln);
		# end Header			
		
		$this->SetLineWidth(.65); # border width		
		$this->Line($lr_margin+.2,$body_top_margin,120-.2,$body_top_margin);		
		
		$this->SetFont('Arial','',8);		
		$this->SetLineWidth(0);		
		$fill = false;
		$body['striped_bg']($this);
		$total_i = count($data)-1;
		foreach($data as $key => $row) {			
			
			// Calculate the height of each body row
			$nb=0;
			foreach ($headers as $i => $h) {
				$nb=max($nb,$this->NbLines($h['width'],$row[array_keys($row)[$i]]));
			}
			$rh=5*$nb;
			
			$pageBreak = $this->CheckPageBreak($rh);

			// $this->SetTextColor(38,50,56);	
			foreach ($headers as $i => $h) {
				if ($key == $total_i) break;
				$body_x = $this->GetX();					
				$body_y = $this->GetY();
				$df = 'D';
				if ($body['striped']) if ($fill) $df = 'DF';
				$this->Rect($body_x,$body_y,$h['width'],$rh,$df);
				$content = iconv('UTF-8', 'ISO-8859-1', ($i>0)?"Php. ".number_format($row[array_keys($row)[$i]],2):$row[array_keys($row)[$i]]);					
				$this->MultiCell($h['width'],5,$spacer.$content,0,'L');
				$this->SetXY($body_x+$h['width'],$body_y);
			}
			if ($key == $total_i) $rh = 2;			
			$this->Ln($rh);	
			$fill = !$fill;		
			
		}
		
		# Total
		$this->SetFont('Arial','B',9);		
		foreach ($headers as $i => $h) {
			$body_x = $this->GetX();					
			$body_y = $this->GetY();
			$content = iconv('UTF-8', 'ISO-8859-1', $data[$total_i]['description']);
			if ($i > 0 )$content = iconv('UTF-8', 'ISO-8859-1', "Php. ".number_format($data[$total_i]['amount'],2));
			$this->MultiCell($h['width'],5,$spacer.$content,0,'L');
			$this->SetXY($body_x+$h['width'],$body_y);
		}	
		
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
		$p->Image("../img/lzds-logo-gray.png",173,25,25);	
	},
	function($p) {
		echo null; # important in include
		$p->SetFont('Arial','B',14);	
		$p->SetXY(18,15);
		$p->darkerText();
		$p->Cell(0,5,"Lord of Zion Divine School",0,1,'L');
		$p->SetFont('Arial','B',12);		
		$p->SetXY(160,15);
		$p->Cell(0,5,"Enrollment Report",0,1,'L');	
		$p->SetFont('Arial','',10);
		$p->darkText();
		$p->SetXY(18,25);
		$p->Cell(0,5,"Paratong, Bacnotan, La Union, 2515",0,1,'L');
		$p->SetXY(18,30);
		$p->Cell(0,5,"Tel. No.: (072) 607 4004",0,1,'L');
		$p->SetDrawColor(120,144,156);
		$p->SetFillColor(120,144,156);
		$p->Rect(18,60,180,0.5,"DF");
		$p->Rect(18,61,180,0.1,"DF");
		$p->Rect(18,100,180,0.1,"DF");
	},
	function($p) { # Name
		global $enrollment;
		$p->SetFont('Arial','',9);	
		$p->SetXY(18,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Name",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(18,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,$enrollment[0]['fullname'],0,1,'L');	
	},
	function($p) { # LRN
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(18,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"LRN",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(18,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,$enrollment[0]['lrn'],0,1,'L');
	},
	function($p) { # Address
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(90,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Address",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(90,71);
		$p->SetTextColor(38,50,56);
		$p->MultiCell(30,5,$enrollment[0]['address'],0,'L',false);	
	},
	function($p) { # Level
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(130,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Level",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(130,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,$enrollment[0]['grade'],0,1,'L');
	},
	function($p) { # Section
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(130,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Section",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(130,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,$enrollment[0]['section'],0,1,'L');	
	},
	function($p) { # School Year
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(160,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"School Year",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(160,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,$enrollment[0]['school_year'],0,1,'L');
	},
	function($p) { # Date
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(160,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Date",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(160,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,date("M j, Y",strtotime($enrollment[0]['enrollment_date'])),0,1,'L');	
	},
	function($p) { # School Fees
		$p->SetFont('Arial','',10);	
		$p->SetXY(19,105);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"School Fees",0,1,'L');	
	},
	function($p) { # Sub Total
		global $enrollment, $sub_total;		
		$p->SetFont('Arial','',9);	
		$p->SetXY(135,105);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Sub Total",0,1,'R');
		$p->SetFont('Arial','',11);	
		$p->SetXY(150,105);
		$p->SetTextColor(38,50,56);
		$p->Cell(45,5,"Php. ".number_format($sub_total,2),0,1,'R');
	},
	function($p) { # Discount
		global $enrollment;	
		$p->SetFont('Arial','',9);	
		$p->SetXY(135,115);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Discount",0,1,'R');
		$p->SetFont('Arial','',11);	
		$p->SetXY(150,115);
		$p->SetTextColor(38,50,56);
		$p->Cell(45,5,"Php. ".number_format($enrollment[0]['discount'],2),0,1,'R');	
	},
	function($p) { # Total
		global $enrollment, $total;	
		$p->SetFont('Arial','',9);
		$p->SetXY(135,125);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Total",0,1,'R');
		$p->SetFont('Arial','',11);
		$p->SetXY(150,125);
		$p->SetTextColor(38,50,56);
		$p->Cell(45,5,"Php. ".number_format($total,2),0,1,'R');
	},
	function($p) { # always last item
		echo null; # important in include
		global $body_top_margin;
		$p->Ln($body_top_margin);		
		if ($p->PageNo()>1) {
			if ($body_top_margin <= 2) $body_top_margin+=5;
			$p->Ln($body_top_margin);
		}
	}
);
$footer = array(
	function($p) {
		echo null; # important in include
	}
);
$headers = array(
	array("width"=>60,"column"=>"Description"),
	array("width"=>40,"column"=>"Amout"),
);
# query here
#
$data = $fees;

$body = array(
	"lr_margin"=>20,
	"top_margin"=>115,	
	"striped"=>false,
	"striped_bg"=>function($p) {
		$p->SetFillColor(223,223,223); # background color		
	},
	"headers"=>$headers,
	"data"=>$data,
	"start"=>function($p) {
		echo null; # important in include
		// Colors, line width and bold font
		$p->SetDrawColor(144,164,174); # border color
		$p->SetFillColor(255,255,255); # background color
		$p->SetTextColor(144,164,174); # font color
		$p->SetLineWidth(0); # border width
		$p->SetFont('Arial','B',8);
	}
);
# end
$pdf = new PDF('P','mm','Letter'); # set here if Portrait(P)/Landscape(L)
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->body($body);
$pdf->Output();

?>