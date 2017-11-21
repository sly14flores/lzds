<?php

// $params = json_decode($_POST['params'],true);

require('../fpdf181/fpdf.php');
require('../db.php');

$con = new pdo_db();

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
				$this->SetFillColor(60,159,223); # background color
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
			}
			
			$this->SetFillColor(223,223,223);		
			foreach ($headers as $i => $h) {
				$body_x = $this->GetX();					
				$body_y = $this->GetY();
				$df = 'D';
				if ($body['striped']) if ($fill) $df = 'DF';
				$this->Rect($body_x,$body_y,$h['width'],$rh,$df);
				$content = iconv('UTF-8', 'ISO-8859-1', $row[array_keys($row)[$i]]);					
				$this->MultiCell($h['width'],5,$content,0,'C');
				$this->SetXY($body_x+$h['width'],$body_y);
			}
			$this->Ln($rh);				
			$fill = !$fill;

		}

	}

}

# start

$top_margin = 5;

$header = array(
	function($p) {
		echo null; # important in include
		$p->SetTextColor(66,66,66);
		$p->SetFont('Arial','',10);
		$p->Cell(0,6,"Republic of the Philippines",0,1,'C');
		$p->SetFont('Arial','B',12);
		$p->Cell(0,6,"PROVINCIAL GOVERNMENT OF LA UNION",0,1,'C');
		$p->SetFont('Arial','',10);
		$p->Cell(0,6,"City of San Fernando",0,1,'C');
		$p->Ln(2);
		$p->SetFont('Arial','B',14);
		$p->Cell(0,7,"List of Employees",0,1,'C');

		$p->Image("../img/pglu-logo.png",10,10,30);
		$p->Image("../img/ILOVELAUNION.png",230,5,40);

		$p->SetDrawColor(0,0,0);	
	}
);

$footer = array(
	function($p) {
		echo null; # important in include
	}
);

$headers = array(
	array("width"=>20,"column"=>"Picture"),
	array("width"=>15,"column"=>"ID"),
	array("width"=>50,"column"=>"Name"),
	array("width"=>25,"column"=>"Gender"),
	array("width"=>30,"column"=>"Department"),
	array("width"=>30,"column"=>"Status of Appointment"),
	array("width"=>35,"column"=>"Position"),
	array("width"=>55,"column"=>"Address")
);

# query here

#

$data = array(
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),
	array(
		"Picture"=>"",
		"ID"=>"",
		"Name"=>"",
		"Gender"=>"",
		"Department"=>"",
		"Status of Appointment"=>"",
		"Position"=>"",
		"Address"=>""
	),	
);

$body = array(
	"lr_margin"=>10,
	"top_margin"=>45,
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
		$p->SetFillColor(60,159,223); # background color
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