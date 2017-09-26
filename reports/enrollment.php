<?php

$params = json_decode($_POST['params'],true);

$filter = $params['filter'];

$params = []; # comment this if production/live

require('../fpdf181/fpdf.php');
require('../db.php');

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
		if ($this->PageNo()>1) {
			if ($top_margin <= 5) $top_margin+=5;
			$this->Ln($top_margin);
		}
		
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
		$headers = $body['headers'];
		$data = $body['data'];
		$this->SetMargins($lr_margin,0);
		$this->Cell(0,5,"",0,1,'L');
		
		// Calculate the height of the header
		$nb=0;
		foreach ($headers as $h) {
			$nb=max($nb,$this->NbLines($h['width'],$h['column']));
		}
		$hh=5*$nb;
	
		// Header
		$header_y = $this->GetY()-15;
		$header_x = $this->GetX();
		$x_stack = 0;
		foreach ($headers as $i => $h) {
			if ($i > 0) {
				$x_stack += $headers[$i-1]['width'];
				$this->SetY($header_y);
				$header_x = $lr_margin+$x_stack;
				$this->SetX($header_x);
			}
			// $this->Rect($header_x,$header_y,$h['width'],$hh,'DF');
			$this->Rect($header_x,$header_y,$h['width'],0.2,'DF');
			$this->Rect($header_x,$header_y+8,$h['width'],0.1,'DF');
			// $this->MultiCell($h['width'],7,$h['column'],1,'C',true);
			if ($i == 0) $this->SetY($header_y-.125); # first column in headers fix
			$this->MultiCell($h['width'],8,$h['column'],0,'C');
		}
		$this->Ln($hh-5);
		
		$fill = false;
		$body['striped_bg']($this);
		foreach($data as $key => $row) {			
			
			// Calculate the height of each body row
			$nb=0;
			foreach ($headers as $i => $h) {
				$nb=max($nb,$this->NbLines($h['width'],$row[array_keys($row)[$i]]));
			}
			$rh=5*$nb;
			
			$this->CheckPageBreak($rh);

			$body_y = $this->GetY();		
			$body_x = $this->GetX();		
			$x_stack = 0;
			$this->SetTextColor(38,50,56);
			foreach ($headers as $i => $h) {
				if ($i > 0) {
					$x_stack += $headers[$i-1]['width'];
					$this->SetY($body_y);
					$body_x = $lr_margin+$x_stack;
					$this->SetX($body_x);
				}
				$df = 'D';
				if ($body['striped']) if ($fill) $df = 'DF';
				if ($key == (count($data)-1)) $this->Rect($body_x,$body_y+6,$h['width'],0.1,$df);
				$this->MultiCell($h['width'],6,$row[array_keys($row)[$i]],0,'C');
			}
			
			if ($key > 0) $this->Ln($rh-5);		
			$fill = !$fill;
			
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
$body_top_margin = 0;
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
		$p->SetFont('Arial','',9);	
		$p->SetXY(18,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Name",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(18,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"Flores, Sylvester Bulilan",0,1,'L');	
	},
	function($p) { # LRN
		$p->SetFont('Arial','',9);	
		$p->SetXY(18,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"LRN",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(18,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"1010101010",0,1,'L');
	},
	function($p) { # Address
		$p->SetFont('Arial','',9);	
		$p->SetXY(90,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Address",0,0,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(90,71);
		$p->SetTextColor(38,50,56);
		$p->MultiCell(30,5,"#77 Lapog Rd., Tanqui, San Fernando City, La Union",0,'L',false);	
	},
	function($p) { # Level
		$p->SetFont('Arial','',9);	
		$p->SetXY(130,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Level",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(130,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"Grade 12",0,1,'L');
	},
	function($p) { # Section
		$p->SetFont('Arial','',9);	
		$p->SetXY(130,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Section",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(130,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"Omega",0,1,'L');	
	},
	function($p) { # School Year
		$p->SetFont('Arial','',9);	
		$p->SetXY(160,65);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"School Year",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(160,71);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"2017-18",0,1,'L');
	},
	function($p) { # Date
		$p->SetFont('Arial','',9);	
		$p->SetXY(160,80);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"Date",0,1,'L');
		$p->SetFont('Arial','',10);	
		$p->SetXY(160,86);
		$p->SetTextColor(38,50,56);
		$p->Cell(0,5,"June 5, 2017",0,1,'L');	
	},
	function($p) { # School Fees
		$p->SetFont('Arial','',10);	
		$p->SetXY(19,105);
		$p->SetTextColor(144,164,174);
		$p->Cell(0,5,"School Fees",0,1,'L');	
	},
	function($p) { # Sub Total
		$p->SetFont('Arial','',9);	
		$p->SetXY(135,105);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Sub Total",0,1,'R');
		$p->SetFont('Arial','',11);	
		$p->SetXY(150,105);
		$p->SetTextColor(38,50,56);
		$p->Cell(30,5,"10,000",0,1,'R');
	},
	function($p) { # Discount
		$p->SetFont('Arial','',9);	
		$p->SetXY(135,115);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Discount",0,1,'R');
		$p->SetFont('Arial','',11);	
		$p->SetXY(150,115);
		$p->SetTextColor(38,50,56);
		$p->Cell(30,5,"5,000",0,1,'R');	
	},
	function($p) { # Discount
		$p->SetFont('Arial','',9);
		$p->SetXY(135,125);
		$p->SetTextColor(144,164,174);
		$p->Cell(20,5,"Total",0,0,'R');
		$p->SetFont('Arial','',11);
		$p->SetXY(150,125);
		$p->SetTextColor(38,50,56);
		$p->Cell(30,5,"5,000",0,0,'R');
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
$data = [
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000),
	array("description"=>"Tuition Fee","amount"=>5000)
];
$body = array(
	"lr_margin"=>20,
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
		$p->SetLineWidth(.1); # border width
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