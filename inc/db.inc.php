<?php if(defined("IN_SITE")) { // Hack protection
############################################## ?>
<?php
	$dbUser = "aveltjen";
	$dbPass = "22aa44bb";
	$dbHost = "localhost";
	$dbName = "supervisie";

	$db =& MDB2::connect("mysql://$dbUser:$dbPass@$dbHost/$dbName");
	if (PEAR::isError($db)) {
		die($db->getMessage());
	}
	
// 	$db = DB::connect("mysql://$dbUser:$dbPass@$dbHost/$dbName");
	
// 	if (DB::isError($db)) {
//  		die($db->getMessage());
// 	}
?>
<?php ###########################################
} else { echo("Hacking Attempt"); } // End     ?>

