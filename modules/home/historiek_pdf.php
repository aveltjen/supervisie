<?php session_start(); define("IN_SITE", true); $self=$_SERVER['PHP_SELF'];
	//********Requirements & Includes***************
	require_once("../oplevering/fpdf.php");
	require_once("myfpdf-table.php");
	require_once("../oplevering/class.fpdftable.php");
	require("../../PEAR/MDB2.php");
	require_once("../../PEAR/HTMLTemplate/IT.php");
	require("inc/users.da.inc.php");
	require("inc/werven.da.inc.php");
	require("inc/meetstaat.da.inc.php");
	require("inc/vorderingen.da.inc.php");

	$ebits = ini_get('error_reporting');
error_reporting($ebits ^ E_NOTICE);
	
	
	//** Profiel ophalen
	$id			= $user["id"];
	$WerfID = $_REQUEST["werf"];
	
	$name = $user["voornaam"]." ".$user["naam"];

	$werf = GetWerfByWerfID($WerfID);
	$werf_omschrijving = $werf["omschrijving"];
	$werfnr = $werf["nummer"];

	//toezichter ophalen
	$toezichterid = GetToezichterByWerf($WerfID);
	$toezichterdata = getUserById($toezichterid["iduser"]);
	
	$toezichter = "".$toezichterdata["voornaam"]." ".$toezichterdata["naam"]."";
	
	//DEEL1 HOOFDING-------------------------------------------------------------------------------------------
		//create the fpdf object and do some initialization
		$oFpdf = new myFpdf();
		$oFpdf->Open();
		$oFpdf->SetAutoPageBreak(true, 20);
		$oFpdf->SetMargins(20, 20, 20);
		$oFpdf->AddPage();
		$oFpdf->AliasNbPages();
		$oFpdf->SetFont('Arial','','9');
		$oFpdf->Image('../home/images/historiek.png',20,10,170,'','','');
		$oFpdf->Image('../home/images/infrax.jpg',140,20,'',12,'','');
		$oFpdf->Ln();
		$ns = wordwrap($werf_omschrijving,48,"\n",true);
		$oFpdf->Write(5,'Project: '.$werfnr.' - '.$ns.'');
		$oFpdf->Ln();
		$oFpdf->Cell(0,6,'Toezichter: '.$toezichter.'');
		$oFpdf->SetFont('Arial','B','10');	
		$oFpdf->Ln(30);

		$oTable = new fpdfTable($oFpdf);

		/**
		 * Set the tag styles
		 */
		$oTable->setStyle("p","times","",10,"130,0,30");
		$oTable->setStyle("b","arial","B",14,"0,0,0");
		$oTable->setStyle("t1","arial","",9,"0,0,0");
		$oTable->setStyle("t2","arial","I",7,"0,0,0");
		$oTable->setStyle("bi","times","BI",12,"0,0,120");
		$oTable->setStyle("t3","arial","U",7,"36,46,243");

		//change multiple values
	$aCustomConfiguration = array(
	        'TABLE' => array(
	                'TABLE_ALIGN'       => 'L',                 //left align
	                'BORDER_COLOR'      => array(0, 0, 0),      //border color
	                'BORDER_SIZE'       => '0.2',               //border size
	        )
	);

	$bCustomConfiguration = array(
	        'TABLE' => array(
	                'TABLE_ALIGN'       => 'L',                 //left align
	                'BORDER_COLOR'      => array(255, 255, 255),      //border color
	                'BORDER_SIZE'       => '1',               //border size
	        )
	);
	
	//DEEL2 POSTENBOEK CONTENT-------------------------------------------------------------------------------------------

	//Initialize the table class, 5 columns with the specified widths
	

	
	//GESELECTEERDE VORDERINGEN DRUKKEN
	if (isset($_SESSION["selecteren"])) {
		$selectie = $_SESSION["selecteren"];
		
		
		foreach ($selectie as $msID){
			
			//postgegevens
			$post = GetPostByID($msID,$WerfID);
			
			$nummer = $post["nummer"];
			$omschrijving = $post["omschrijving"];
			$eenheden = $post["eenheden"];
			$hoeveelheid = $post["voorziene_hv"];
			
			$oTable->initialize(array(170),$bCustomConfiguration);

			$aRow = Array();
			
			$aRow[0]['BACKGROUND_COLOR'] = array(255, 255, 255);
			$aRow[0]['TEXT'] = "Postnr. ".$nummer.": ".$omschrijving."";
			$aRow[0]['TEXT_ALIGN'] = "L";
			$aRow[0]['TEXT_TYPE'] = "B";
			$aRow[0]['TEXT_SIZE'] = "10";
			$aRow[0]['LINE_SIZE'] = "10";



			$oTable->addRow($aRow);
			
			//close the table
			$oTable->close();
			


			//ALLE PERIODES OPZOEKEN
			
			
			
			$vorderingen2 = GetAllVorderingenDatumByMsID($msID,$WerfID);
			
			
			while($vordering2 = $vorderingen2->fetchrow(MDB2_FETCHMODE_ASSOC)){
				
					$periode = $vordering2["vorderingen_m_y"];
						
					//TOTAAL van periode
					$totaal = 0;
					$vorderingen3 = GetAllVorderingenByPeriode($msID,$periode,$WerfID);
					while($vordering3 = $vorderingen3->fetchrow(MDB2_FETCHMODE_ASSOC)){
						
						$uitgevoerd = $vordering3["uitgevoerd"];
						$totaal = $totaal + $uitgevoerd;
					}

					$oTable->initialize(array(20),$aCustomConfiguration);

					$vs = GetVS($periode,$WerfID);


					$bRow[0]['BACKGROUND_COLOR'] = array(236, 185, 124);
					$bRow[0]['TEXT'] = "VS ".$vs.": ".$periode."";
					$bRow[0]['TEXT_ALIGN'] = "L";

					$oTable->addRow($bRow);

					$cRow[0]['BACKGROUND_COLOR'] = array(255, 255, 255);
					$cRow[0]['TEXT'] = "".$totaal."";
					$cRow[0]['TEXT_ALIGN'] = "L";

					$oTable->addRow($cRow);
			
					//close the table
					$oTable->close();
					$oFpdf->Ln(1);
					
			}


					
				$oFpdf->Ln(10);
			
			
		}
		

	}else{
		
	}
	
	//********************* Template Tonen *************
	
	
	$filename = "historiek_".$werfnr.".pdf";


	//send the pdf to the browser
	$oFpdf->Output($filename,'D');
	
?>