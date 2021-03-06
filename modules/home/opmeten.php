<?php session_start(); define("IN_SITE", true); $self=$_SERVER['PHP_SELF'];$root = $_SERVER['DOCUMENT_ROOT'];
	//********Requirements & Includes***************
	require("../../PEAR/MDB2.php");
	require_once "../../PEAR/HTTP/Upload.php";
	require_once("../../PEAR/HTMLTemplate/IT.php");
	require("inc/users.da.inc.php");
	require("inc/werven.da.inc.php");
	require("inc/meetstaat.da.inc.php");
	require("inc/opmetingen.da.inc.php");
	require("inc/vorderingen.da.inc.php");

	//*********Check user session***************	
	if(!isset($_SESSION["user"])){
		header("Location: ../../index.php");
		exit;
	}else{
		$user = $_SESSION["user"];	
	}
	//*********WerfID ophalen***************
	
	//$werf = $_REQUEST["werf"];
	
	//*********Template modifications***************
	$tpl = new HTML_Template_IT("./");
	$tpl->loadTemplatefile("opmeten.tpl.php");
	
	
	//*******Template specific code*************
	
	//** Uitloggen
	if($_REQUEST["action"]=="logout"){
		session_destroy();
		header("Location: ../../index.php");
	}
	
	//** Profiel ophalen
	$id			= $user["id"];
		
	$tpl->setVariable("titel","");
	
	//** Geselcteerde post ophalen
	$msID = $_REQUEST["msID"];
	$werf = $_REQUEST["werf"];
	$tpl->setVariable("msID",$msID);
	$tpl->setVariable("werf",$werf);
	
//VORIGE & VOLGENDE POST
	
	$vorigepost = GetPreviousPostId($msID,$werf);

	if($vorigepost == $msID){
		$tpl->setVariable("vorigepost","");
	}else{
		$tpl->setVariable("vorigepost","<a href='?werf=".$werf."&msID=".$vorigepost."'>< vorige</a>");
	}

	$volgendepost = GetNextPostId($msID,$werf);

	if($volgendepost == $msID){
		$tpl->setVariable("volgendepost","");
	}else{
		$tpl->setVariable("volgendepost","<a href='?werf=".$werf."&msID=".$volgendepost."'>volgende ></a>");
	}

	
	$post = GetPostByID($msID, $werf);
	$tpl->setVariable("nummer",$post["nummer"]);
	$tpl->setVariable("omschrijving",$post["omschrijving"]);
	$tpl->setVariable("eenheden",$post["eenheden"]);
	$tpl->setVariable("hoeveelheid",$post["voorziene_hv"]);
	
	
	if($_REQUEST["lastopmeting"]==1){
	//LAATSTE OPMETING OPHALEN
	$lastopmeting= GetLastInsertO($werf,$id);
	
	$tpl->setVariable("lastomschrijving",$lastopmeting["berekening"]);
	$tpl->setVariable("lastopgemeten",$lastopmeting["uitgevoerd"]);
	}
	
	//VORDERING WIJZIGEN
	if($_REQUEST["action"]== "wijzig"){
		//VORDERINGGEGEVENS OPHALEN
		$werf = $_REQUEST["werf"];
		$vid = $_REQUEST["vid"];
		$opmeting = GetOpmetingByVid($vid,$werf);
		
		$bijlage_old = $opmeting["bijlage1"];
		$datum_old = $opmeting["datum"];
		$omschrijving_old = $opmeting["berekening"];
		$uitgevoerd_old = number_format($opmeting["uitgevoerd"],3,',','');
// 		$uitgevoerd_old = str_replace(".", ",", $opmeting["uitgevoerd"]);
		
		$tpl->setVariable("txt_wijzig","
		
		<table width='100%' border='0' class='tekstnormal' bgcolor='#fae3e3'>
		<tr>
			<td align='center' colspan='2'><img src='images/exclamation.png' > Wenst u de opmeting te wijzigen?</td>
		</tr>
		<tr>
			<td colspan='2' align='center'>
				<form name='wijzig' action='?werf={werf}&msID={msID}&wijzig=yes&vid=".$vid."' method='POST' enctype='multipart/form-data'>
                            		
                            		<table width='650' class='tekstnormal'>
                            			<tr>
                            				<td>Omschrijving:</td>
                                            <td><input value='".$omschrijving_old."' type='text' size='50' name='omschrijving' /> <INPUT TYPE='button' VALUE=' = ' onClick='compute(this.form)'></td>
                            				<td align='right'><img src='images/disk-black.png'> <a href='#' onClick='document.wijzig.submit();'>wijzig</a>&nbsp;&nbsp;<img src='images/cross-shield.png'> <a href='?werf={werf}&msID={msID}'>Annuleren</a></td>
                            			</tr>
                            			<tr>
                            				<td>Opgemeten:</td>
                                            <td><input value='".$uitgevoerd_old."' type='text' size='10' name='opgemeten' /></td>
                                            <td></td>
                            			</tr>
                            			<tr>
                            				<td>Bijlage: <img src='images/pin--plus.png'></td>
                                            <td><input type='file' name='f' size='25'></td>
                                            <td><input type='hidden' name='bijlage_old' value='".$bijlage_old."'><input type='checkbox' name='attach' value='none'/>bijlage verwijderen en niet vervangen</td>
                            			</tr>
                            		</table>
                            		</form>
			</td>
		</tr>
		</table>
		<br>
		
		");
		
	}

	if($_REQUEST["wijzig"]== "yes"){
		
		$vid 				= $_REQUEST["vid"];
		$datum_new 			= date("Y-m-d");
		$omschrijving_new 	= $_REQUEST["omschrijving"];
		$uitgevoerd_new		= str_replace(",", ".", $_REQUEST["opgemeten"]);
		$bijlage_old		= $_REQUEST["bijlage_old"];
		$attach				= $_REQUEST["attach"];
		
		
			//upload uitvoeren
		$upload = new HTTP_Upload("nl");
		$file = $upload->getFiles("f");
			
			if ($file->isValid()) {
				 $file->setName("uniq");
				 $bijlage_new = $file->getProp("name");
				 
			    $moved = $file->moveTo("".$root."/files_dir/uploads/opmetingen".$id."/");
			    if (!PEAR::isError($moved)) {
			    	unlink("".$root."/files_dir/uploads/opmetingen".$id."/".$bijlage_old."");
			    	UpdateOpmeting($vid,$datum_new,$omschrijving_new,$uitgevoerd_new,$bijlage_new,$werf);
			    	
			    } else {
			        echo $moved->getMessage();
			    }
			} elseif ($file->isMissing()) {
	
					if ($attach == "none"){
						unlink("".$root."/files_dir/uploads/opmetingen".$id."/".$bijlage_old."");
						$bijlage_old = "";
						UpdateOpmeting($vid,$datum_new,$omschrijving_new,$uitgevoerd_new,$bijlage_old,$werf);
					}else{
						UpdateOpmeting($vid,$datum_new,$omschrijving_new,$uitgevoerd_new,$bijlage_old,$werf);
					}
			   		
			} elseif ($file->isError()) {
			    echo $file->errorMsg();
			}
			
			//Update meetstaat
			//OPMETINGEN OPHALEN
			$opmetingen = GetOpmetingenByPost($msID,$werf);
			$oh = 0;
			while($opmeting = $opmetingen->fetchrow(MDB2_FETCHMODE_ASSOC)){
			
				$oh = $oh + $opmeting["uitgevoerd"];
			}
			UpdateOH($oh,$msID,$werf);
			
		
	}else{
		
	}
	
	//opmeting VERWIJDEREN
	if($_REQUEST["action"]== "delete"){
		
		$tpl->setVariable("txt_delete","
		
		
		<table width='100%' border='0' class='tekstnormal' bgcolor='#fae3e3'>
		<tr>
			<td align='center' colspan='2'><img src='images/exclamation.png' > definitief verwijderen?</td>
		</tr>
		<tr>
			<td colspan='2' align='center'>
				<table width='150' border='0'>
				<tr>
					<td width='50%'><img src='images/tick-shield.png'> <a href='?msID=".$msID."&werf=".$WerfID."&delete=yes&vid=".$_REQUEST["vid"]."'>Ja</a></td>
					<td width='50%'><img src='images/cross-shield.png'> <a href='?msID=".$msID."&werf=".$WerfID."'>Nee</a> </td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		");
			
	}
	

	//OPMETING VERWIJDEREN
	if($_REQUEST["action"]== "delete"){
		
		$tpl->setVariable("txt_delete","
		
		
		<table width='100%' border='0' class='tekstnormal' bgcolor='#fae3e3'>
		<tr>
			<td align='center' colspan='2'><img src='images/exclamation.png' > definitief verwijderen?</td>
		</tr>
		<tr>
			<td colspan='2' align='center'>
				<table width='150' border='0'>
				<tr>
					<td width='50%'><img src='images/tick-shield.png'> <a href='?msID=".$_REQUEST["msID"]."&werf=".$_REQUEST["werf"]."&delete=yes&vid=".$_REQUEST["vid"]."&bijlage=".$_REQUEST["bijlage"]."'>Ja</a></td>
					<td width='50%'><img src='images/cross-shield.png'> <a href='?msID=".$_REQUEST["msID"]."&werf=".$_REQUEST["werf"]."'>Nee</a> </td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		");
			
	}
	
	if($_REQUEST["delete"]== "yes"){
		
		$vid = $_REQUEST["vid"];
		$bijlage = $_REQUEST["bijlage"];
		
		
		deleteOpmeting($vid);
		//Update meetstaat
		//OPMETINGEN OPHALEN
		$opmetingen = GetOpmetingenByPost($msID,$werf);
		$oh = 0;
		while($opmeting = $opmetingen->fetchrow(MDB2_FETCHMODE_ASSOC)){
				
			$oh = $oh + $opmeting["uitgevoerd"];
		}
		UpdateOH($oh,$msID,$werf);
			if($bijlage==""){
				
			}else{
				unlink("".$root."/files_dir/uploads/opmetingen".$id."/".$bijlage."");
			}
		}else {
			
		}
		
		//DOORSTUREN NAAR VORDERING
		if($_REQUEST["action"]== "vorder"){

			$tpl->setVariable("txt_delete","


			<table width='100%' border='0' class='tekstnormal' bgcolor='#fae3e3'>
			<tr>
				<td align='center' colspan='2'><img src='images/exclamation.png' > opmeting met onderstaand ID doorsturen naar vordering?<br><b>ID " .$_REQUEST["vid"]."</b></td>
			</tr>
			<tr>
				<td colspan='2' align='center'>
					<table width='150' border='0'>
					<tr>
						<td width='50%'><img src='images/tick-shield.png'> <a href='?msID=".$msID."&werf=".$werf."&vorder=yes&vid=".$_REQUEST["vid"]."'>Ja</a></td>
						<td width='50%'><img src='images/cross-shield.png'> <a href='?msID=".$msID."&werf=".$werf."'>Nee</a> </td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			<br>
			");
		}	
		
		if($_REQUEST["vorder"]== "yes"){

			$werf = $_REQUEST["werf"];
			$vid = $_REQUEST["vid"];
			$user = $user["id"];

			$row = GetOpmetingByVid($vid,$werf);

			$omschrijving = $row["berekening"];
			$uitgevoerd = $row["uitgevoerd"];

			$datum = date("Y-m-d");
						
			$date = explode("-",$datum); 
			$timestamp = mktime(0,0,0,$date[1],$date[2],$date[0]);
			$periode = date("m-Y", $timestamp);
			
			addVordering($werf,$user,$msID,$vs,$datum,$omschrijving,$uitgevoerd,$periode);

			//UPDATE MEETSTAAT
			//VORDERINGEN OPHALEN

			$vorderingen = GetVorderingenByPost($msID,$werf);
			$gh = 0;
				while($vordering = $vorderingen->fetchrow(MDB2_FETCHMODE_ASSOC)){
					$gh = $gh + $vordering["uitgevoerd"];
				}

			UpdateGH($gh,$msID,$werf);

		}
	
	//OPMETING TOEVOEGEN
	if($_REQUEST["action"]=="add"){
		$werf = $_REQUEST["werf"];
	
		$user = $user["id"];
		$msID = $_REQUEST["msID"];
		$omschrijving = mysql_real_escape_string($_REQUEST["omschrijving"]);
		$uitgevoerd = str_replace(",", ".", $_REQUEST["opgemeten"]);
		$datum = date("Y-m-d"); 
	
		
		//upload uitvoeren
		$upload = new HTTP_Upload("en");
		$file = $upload->getFiles("f");
		
		
		//checken of document bestaat
		$bijlage = $file->getProp("name");
			
			if ($file->isValid()) {
				 $file->setName("uniq");
				 $bijlage = $file->getProp("name");
				 
			    $moved = $file->moveTo("".$root."/files_dir/uploads/opmetingen".$id."");
			    if (!PEAR::isError($moved)) {
			    	addOpmeting($werf,$user,$msID,$datum,$omschrijving,$uitgevoerd,$bijlage);
			    	
			    } else {
			        echo $moved->getMessage();
			    }
			} elseif ($file->isMissing()) {
					$bijlage = "";
			   		addOpmeting($werf,$user,$msID,$datum,$omschrijving,$uitgevoerd,$bijlage);
					
			} elseif ($file->isError()) {
			    echo $file->errorMsg();
			}
// 							//Update meetstaat
// 							//OPMETINGEN OPHALEN
							$opmetingen = GetOpmetingenByPost($msID,$werf);
// 							
							$oh = 0;
								while($opmeting = $opmetingen->fetchrow(MDB2_FETCHMODE_ASSOC)){
			
									$oh = $oh + $opmeting["uitgevoerd"];	
								}
							UpdateOH($oh,$msID,$werf);

	}
	//OPMETINGEN OPHALEN
	$opmetingen = GetOpmetingenByPost($msID,$werf);
	$tpl->setCurrentBlock("opmetingen");
	$totaal = 0;
	while($opmeting = $opmetingen->fetchrow(MDB2_FETCHMODE_ASSOC)){
		$tpl->setVariable("icon","<img src='images/arrow-curve-000-left.png'>");
		$tpl->setVariable("datum",$opmeting["datum"]);
		 
		$opgemeten = number_format($opmeting["uitgevoerd"],3,',','');
		
		if($opmeting["bijlage1"]==""){
			$tpl->setVariable("bijlage","");
		}else{
			$tpl->setVariable("bijlage","<a href='../../../files_dir/uploads/opmetingen".$id."/".$opmeting["bijlage1"]."' target='_blank'><img src='images/attach.png'></a>");
		}
		
		$tpl->setVariable("berekening",wordwrap($opmeting["berekening"], 60, "\n", true));
		$tpl->setVariable("gemeten",$opgemeten);
		$tpl->setVariable("ID",$opmeting["id"]);
		$tpl->setVariable("delete","<a href='?msID=".$msID."&werf=".$werf."&action=delete&vid=".$opmeting["id"]."&bijlage=".$opmeting["bijlage1"]."'><img src='images/cross.png'></a>");
		$tpl->setVariable("wijzig","<a href='?msID=".$msID."&werf=".$werf."&action=wijzig&vid=".$opmeting["id"]."'><img src='images/bin--pencil.png'></a>");
		$tpl->setVariable("vorder","<a href='?msID=".$msID."&werf=".$werf."&action=vorder&vid=".$opmeting["id"]."'><img src='images/book--arrow.png'></a>");
		
		$totaal = $totaal + $opmeting["uitgevoerd"];
		$tpl->setVariable("totaal",number_format($totaal,3,',',''));
		$tpl->parseCurrentBlock();
	}
	//GEEN OPMETINGEN
	$num = $opmetingen->numRows();
			
				if($num > 0){
					$tpl->setVariable("geenopm","");
// 					$tpl->setVariable("printen2","<img src='images/document-pdf.png'>  <a href='opmetingen_post_pdf.php?werf=".$werf."&msID=".$msID."' target='_blank'>download PDF</a>");
				}else{
					$tpl->setVariable("printen2","");
					$tpl->setVariable("geenopm","
					<tr class='drukrows'>
						<td colspan='6'>Geen opmetingen</td>
					</tr>
					");	
				}
	
	//********************* Template Tonen *************
	
	$tpl->show(); //moet ge doen, anders ziede niet
	
?>