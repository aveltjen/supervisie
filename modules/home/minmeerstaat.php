<?php session_start(); define("IN_SITE", true);define('EUR',chr(128));
	require_once("../oplevering/fpdf.php");
	require_once("myfpdf-table_minmeer.php");
	require_once("../oplevering/class.fpdftable.php");
	require("../../PEAR/MDB2.php");
	require("inc/users.da.inc.php");
	require("inc/werven.da.inc.php");
	require("inc/meetstaat.da.inc.php");
	require("inc/vorderingen.da.inc.php");
	require("pdf/class.ezpdf.php");
	
	//$ebits = ini_get('error_reporting');
	//error_reporting($ebits ^ E_NOTICE);

	//REQUESTS
	$werf = $_REQUEST["werven_ID"];
	$userid = $_REQUEST["id"];

	//USERINFO
	//toezichter ophalen
		$toezichterid = GetToezichterByWerf($werf);
		$toezichterdata = getUserById($toezichterid["iduser"]);


		$naam = "".$toezichterdata["voornaam"]." ".$toezichterdata["naam"]."";

	//WERFINFO
	$werfdata = GetWerfByWerfID($werf);
	$project = "".$werfdata["omschrijving"]."";
	$wnummer = $werfdata["nummer"];


	//create the fpdf object and do some initialization
	$oFpdf = new myFpdf();
	$oFpdf->Open();
	$oFpdf->SetAutoPageBreak(true, 20);
	$oFpdf->SetMargins(20, 20, 20);
	$oFpdf->AddPage();
	$oFpdf->AliasNbPages();
	$oFpdf->SetFont('Arial','','9');
	$oFpdf->Image('../home/images/docbalk-minmeer.png',20,10,180,'','','');
	$oFpdf->Image('../home/images/infrax.jpg',140,20,'',12,'','');
	$oFpdf->SetFont('','B','12');
	$oFpdf->Cell(10,7,'Min/meer staat: '.$vsnum.'');
	$oFpdf->SetFont('','','11');
	$oFpdf->Ln();
	$ns = wordwrap($project,48,"\n",true);
	$oFpdf->Write(5,'Project: '.$wnummer.' - '.$ns.'');
	$oFpdf->Ln();
	$oFpdf->Cell(10,7,'Toezichter: '.	$naam.'');
	$oFpdf->Ln();
	$oFpdf->SetFont('Arial','B','10');	
	// $oFpdf->Line(20, 52, 200,52);
	$oFpdf->Ln();
	$oFpdf->SetFont('','','10');
	
	//AANBESTEDING
		$posten = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE nummer NOT LIKE 'V%' ORDER BY ID");
		$vtotaal2 = 0;
		while($post = $posten->fetchrow(MDB2_FETCHMODE_ASSOC)){
			$vh = $post["voorziene_hv"];
			$eprijs = $post["prijs"];

			$prijs = $vh*$eprijs;

			$vtotaal2 = $vtotaal2 + $prijs;
		}
	
	$oFpdf->Cell(20,'','Totaal aanbestedingsbedrag: '.EUR.' '.number_format($vtotaal2, 2, ',', ' ').'','','','L');
	$oFpdf->Ln();
	

	$oFpdf->Line(20, 68, 190,68);
	
	
	
	#START DATA
			$posten = GetVorderingsstaatByWerf($werf);

			while($post = $posten->fetchrow(MDB2_FETCHMODE_ASSOC)){
				
				$topgemeten = $post["totopgemeten"];
				$tgevorderd = $post["totgevorderd"];

				$eprijs = $post["prijs"];
				$prijs= $topgemeten * $eprijs;
				$bedrag= "".EUR." ".number_format($prijs, 2, ',', ' ')." (EP = ".EUR." ".number_format($eprijs, 2, ',', ' ').")";
				
				$minmeer = $topgemeten - $tgevorderd;
				$saldo = $minmeer * $eprijs;
				// echo $saldo."<br>";
				//Totaal bedrag Min/Meer
				$tsaldo = $saldo + $tsaldo;

				//Totaal bedrag opgemeten
				$tbedrag= $prijs + $tbedrag;
				
			}
		
	$oFpdf->Cell(65,10,'Totaal bedrag opgemeten: '.EUR.' '.number_format($tbedrag, 2, ',', ' ').'','','','R');
	$oFpdf->Ln();
	$oFpdf->SetFont('','B','12');
	$oFpdf->Cell(170,0,'Totaal bedrag min/meer: '.EUR.' '.number_format($tsaldo, 2, ',', ' ').'','','','R');
	$oFpdf->Ln(20);
//	$oFpdf->SetFont('','','11');
	
	

	$oTable = new fpdfTable($oFpdf);

	/**
	 * Set the tag styles
	 */
	$oTable->setStyle("p","times","",10,"130,0,30");
	$oTable->setStyle("b","arial","B",9,"0,0,0");
	$oTable->setStyle("t1","arial","",9,"0,0,0");
	$oTable->setStyle("t2","arial","I",9,"0,0,0");
	$oTable->setStyle("bi","times","BI",12,"0,0,120");
	$oTable->setStyle("t3","arial","U",9,"36,46,243");
	$oTable->setStyle("kop","arial","B",9,"0,0,0");

	
	//change multiple values
	$aCustomConfiguration = array(
	        'TABLE' => array(
	                'TABLE_ALIGN'       => 'C',                 //left align
	                'BORDER_COLOR'      => array(0, 0, 0),      //border color
	                'BORDER_SIZE'       => '0.2',               //border size
	        )
	);

	//LOAD DATA
			//Initialize the table class, 5 columns with the specified widths
			$oTable->initialize(array(20, 30, 30, 30, 30, 40), $aCustomConfiguration);

			$aRow = Array();
			$aRow[0]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[0]['TEXT'] = "<kop>Post Nr.</kop>";
			$aRow[0]['TEXT_ALIGN'] = "C";
			$aRow[0]['BORDER_COLOR'] = array(0, 0, 0);
			
			$aRow[1]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[1]['TEXT'] = "<kop>Verm. Hv.</kop>";
			$aRow[1]['TEXT_ALIGN'] = "C";
			$aRow[1]['BORDER_COLOR'] = array(0, 0, 0);

			$aRow[2]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[2]['TEXT'] = "<kop>Tot. gevorderd</kop>";
			$aRow[2]['TEXT_ALIGN'] = "C";
			$aRow[2]['BORDER_COLOR'] = array(0, 0, 0);
			
			$aRow[3]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[3]['TEXT'] = "<kop>Min/meer</kop>";	
			$aRow[3]['TEXT_ALIGN'] = "C";
			$aRow[3]['BORDER_COLOR'] = array(0, 0, 0);
			
			$aRow[4]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[4]['TEXT'] = "<kop>Eindtotaal</kop>";
			$aRow[4]['TEXT_ALIGN'] = "C";
			$aRow[4]['BORDER_COLOR'] = array(0, 0, 0);
			
			$aRow[5]['BACKGROUND_COLOR'] = array(249,145,56);
			$aRow[5]['TEXT'] = "<kop>Bedrag (".EUR." )</kop>";
			$aRow[5]['TEXT_ALIGN'] = "C";
			$aRow[5]['BORDER_COLOR'] = array(0, 0, 0);
			
			$oTable->addRow($aRow);
			
	
			$tbedrag = 0;
			$tsaldo = 0;

			#START DATA
			$posten = GetVorderingsstaatByWerf($werf);

			$rijnummer = 0;
			while($post = $posten->fetchrow(MDB2_FETCHMODE_ASSOC)){
				$aRow = Array();
				
				//1# POSTEN NUMMER AFDRUKKEN IN CEL 1	
			
				$postnummer = $post["nummer"];
				
				//2# Vermoedelijke hoeveelheid
				$vmhv = $post["voorziene_hv"];	

				//3# Totaal gevorderd
				$tgevorderd = $post["totgevorderd"];

				//5# Totaal opgemeten/ eindtotaal
				$topgemeten = $post["totopgemeten"];

				//4# Min/Meer
				$minmeer = $topgemeten - $tgevorderd;

				//5# BEDRAG
				$eprijs = $post["prijs"];
				$prijs= $topgemeten * $eprijs;
				$bedrag= "".EUR." ".number_format($prijs, 2, ',', ' ')." (EP = ".EUR." ".number_format($eprijs, 2, ',', ' ').")";
				
				//Totaal staat
				$tbedrag= $prijs + $tbedrag;

				//BUILDING ROWS
				$aRow[0]['TEXT'] = $postnummer;
				$aRow[0]['TEXT_ALIGN'] = "C";
				$aRow[0]['BORDER_COLOR'] = array(0, 0, 0);
				
				if($totaal > $vmhv){
					$aRow[0]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[0]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}

				$aRow[1]['TEXT'] = number_format($vmhv, 3, ',', ' ');
				$aRow[1]['TEXT_ALIGN'] = "C";
				$aRow[1]['BORDER_COLOR'] = array(0, 0, 0);
				if($totaal > $vmhv){
					$aRow[1]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[1]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}			
				
				
				$aRow[2]['TEXT'] = number_format($tgevorderd,'3',',',' ');
				$aRow[2]['TEXT_ALIGN'] = "C";
				$aRow[2]['BORDER_COLOR'] = array(0, 0, 0);
				if($totaal > $vmhv){
					$aRow[2]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[2]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}
				
				
				$aRow[3]['TEXT'] = number_format($minmeer,'3',',',' ');
				$aRow[3]['TEXT_ALIGN'] = "C";
				$aRow[3]['BORDER_COLOR'] = array(0, 0, 0);
				if($totaal > $vmhv){
					$aRow[3]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[3]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}
				
				
				$aRow[4]['TEXT'] = number_format($topgemeten,'3',',',' ');
				$aRow[4]['TEXT_ALIGN'] = "C";
				$aRow[4]['BORDER_COLOR'] = array(0, 0, 0);
				if($totaal > $vmhv){
					$aRow[4]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[4]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}
				
				
				$aRow[5]['TEXT'] = $bedrag;
				$aRow[5]['TEXT_ALIGN'] = "C";
				$aRow[5]['BORDER_COLOR'] = array(0, 0, 0);
				if($totaal > $vmhv){
					$aRow[5]['BACKGROUND_COLOR'] = array(254, 206, 200);
				}else{
					if($rijnummer % 2){
						$aRow[5]['BACKGROUND_COLOR'] = array(224, 235, 255);
					}
				}
				
				
	 		
			$oTable->addRow($aRow);
			$rijnummer++;
		}
		

//Column titles
//$header=array('Post Nr.','Vorige Hv.','Huidge Hv.','Totale Hv.','Bedrag ('.EUR.' )');
//Data loading
// $pdf->Cell(125,7,'DOC.NR.3');
// $pdf->SetFont('','B','12');
// $pdf->Cell(0,7,'Vorderingstaat: '.$vsnum.'');
// $pdf->Ln();
// $pdf->SetFont('','','11');
// $pdf->Cell(0,7,'Periode: '.$periode.'');
// $pdf->Ln();
// $ns = wordwrap($project,50,"\n",true);
// $pdf->Write(5, $ns);
// $pdf->Ln();
// $pdf->Cell(0,6,'Toezichter: '.$naam.'');
// $pdf->Ln();
// $pdf->Line(10, 45, 200,45);
// $pdf->Ln();
// $pdf->SetFont('','','10');
// $pdf->Cell(20,'','Totaal aanbestedingsbedrag: '.EUR.' '.number_format($vtotaal2, 2, ',', ' ').'','','','L');
// $pdf->Ln();
// $pdf->Cell(20,10,'Totaal vorige vorderingen: '.EUR.' '.number_format($vtotaal, 2, ',', ' ').'','','','L');
// $pdf->Ln();
// $pdf->Line(10, 60, 200,60);
// $pdf->SetFont('','B','12');
// $pdf->Cell(180,20,'Totaal bedrag vorderingstaat: '.EUR.' '.number_format($tbedrag, 2, ',', ' ').'','','','R');
// $pdf->Ln();
// $pdf->SetFont('','','11');

//close the table
$oTable->close();
$oFpdf->Ln(10);

$filename = "minmeerstaat_".$wnummer.".pdf";


//send the pdf to the browser
$oFpdf->Output($filename,'D');
?>