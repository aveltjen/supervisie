<?php if(defined("IN_SITE")) { // Hack protection
############################################## ?>
<?php
       require("../../inc/db.inc.php");
		
		function GetFullVorderingsstaatByWerf($werf){
			global $db;
			

			$vslist = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." ");
			
			return $vslist;
		}

		function GetVorderingsstaatByWerf($werf){
			global $db;
				
		
			$vslist = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE nummer !=''");
			
			return $vslist;
		}
		
		function GetFullVorderingsstaatByWerfOverschreiding($werf){
			global $db;
				
		
			$vslist = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE totGevorderd > voorziene_HV AND nummer !=''");
			
			return $vslist;
		}
		
		function GetFullVorderingsstaatByWerfNihil($werf){
			global $db;
		
		
			$vslist = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE totGevorderd = '0' OR totGevorderd IS NULL AND nummer != ''");
		
			return $vslist;
		}
		
		function GetPostByID($msID, $werf){
			global $db;
			
			$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE ID = '$msID'");
			$post = $result->fetchrow(MDB2_FETCHMODE_ASSOC);
			
			return $post;
		}
		
		//AANBESTEDINGSBEDRAG OPHALEN
		function GetAanbestedingsbedrag($werf){
			global $db;
			global $totaal;
			
			
				$posten = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE nummer NOT LIKE 'V%' ORDER BY ID");
								
					$totaal = 0;
					while($post = $posten->fetchrow(MDB2_FETCHMODE_ASSOC)){
						$vh = $post["voorziene_hv"];
						$eprijs = $post["prijs"];
						
						$prijs = $vh*$eprijs;
						
						$totaal = $totaal + $prijs;
					}
				
			return $totaal;
		}
		
		function UpdateGH($gh,$msID,$werf){
			global $db;
		
			$db->query("UPDATE v_meetstaat_werf_".$werf." SET totGevorderd = '$gh' WHERE ID = '$msID'");
		
		}
		
		function UpdateOH($oh,$msID,$werf){
			global $db;
		
			$db->query("UPDATE v_meetstaat_werf_".$werf." SET totOpgemeten = '$oh' WHERE ID = '$msID'");
		
		}

		function CheckIfPost($msID,$werf){
			global $db;
			global $werf;

			$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE ID = '$msID'");
			$post = $result->fetchrow(MDB2_FETCHMODE_ASSOC);

			$nummer = $post["nummer"];
			$vmhv = $post["voorziene_hv"];

			if($nummer == ""){
				return 0;
			}else{
				if($vmhv == 0){
					return 0;
				}else{
					return 1;
				}
				
			}

		}

		function CheckFirstPost($werf){
			global $db;
			global $werf;
			
			$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE nummer != '' AND voorziene_HV != '' ORDER BY ID ASC LIMIT 0,1");
			$row= $result->fetchrow(MDB2_FETCHMODE_ASSOC);
			
			//print_r($row);
			$firstpost = $row["id"];
		
			//Checken of msID post is, anders verder aftellen tot post wordt erkend
			while(CheckIfPost($firstpost,$werf)!= 1){
				$firstpost++;
			}

		
			return $firstpost;
		}

		function CheckLastPost($werf){
			global $db;
			global $werf;
			
			$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE nummer != '' AND voorziene_HV != '' ORDER BY ID DESC LIMIT 0,1");
			$row= $result->fetchrow(MDB2_FETCHMODE_ASSOC);
			
			$firstpost = $row["id"];
		
			//Checken of msID post is, anders verder aftellen tot post wordt erkend
			while(CheckIfPost($firstpost,$werf)!= 1){
				$firstpost--;
			}

		
			return $firstpost;
			
			
		}

		function GetPreviousPostId($msID,$werf){
			global $db;
			global $werf;

			if(CheckFirstPost($werf) == $msID){
				return $msID;
			}else{

				//Vorige msID vastleggen
				$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE ID < ".$msID." AND nummer != '' AND voorziene_HV != '' ORDER BY ID DESC LIMIT 0,1");
				$row= $result->fetchrow(MDB2_FETCHMODE_ASSOC);

				$result = $row["id"];
			}
				return $result;
			
		
		}


		function GetNextPostId($msID,$werf){
			global $db;
			global $werf;

			if(CheckLastPost($werf) == $msID){
				return $msID;
			}else{
				//Volgende msID vastleggen

				$result = $db->query("SELECT * FROM v_meetstaat_werf_".$werf." WHERE ID > ".$msID." AND nummer != '' AND voorziene_HV != '' ORDER BY ID ASC LIMIT 0,1");
				$row= $result->fetchrow(MDB2_FETCHMODE_ASSOC);

				$result = $row["id"];
			}

				return $result;


		
		}


		
?>
<?php ###########################################
} else { echo("Hacking Attempt"); } // End     ?>
