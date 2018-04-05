<?php

		
		function email_webmaster($meetstaat,$nummer,$omschrijving,$fullname){


			$to      = 'info@djandyveltjen.be,gerry.veltjen@hotmail.be,eddy@owt.be';
			$subject = 'Nieuwe werf '.$nummer.' opladen';
			$headers = 'From: admin@supervisie.owt.be' . "\r\n" .
			    'Reply-To: info@djandyveltjen.be' . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			// message
			$message = "
			<html>
			<head>
			 
			</head>
			<body>
			<p>Administrator,</p>
			Is het mogelijk de volgende werf online te plaatsen!!!
			<p>
			<b>toezichter:</b> ".$fullname."<br>
			<b>werfnummer:</b> ".$nummer."<br>
			<b>omschrijving:</b> ".$omschrijving."<br>
			<b>bestand:</b> <a href='http://supervisie.owt.be/files_dir/uploads/meetstaten/".$meetstaat."'>".$meetstaat."</a>
			</p>
			<p>Vriendelijke groeten,</p>
			Supervisie
			</body>
			</html>
			";
			
			
			
			mail($to, $subject, $message, $headers);
		}
	
?>

