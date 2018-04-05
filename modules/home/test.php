<?php

			

				//Volgende msID vastleggen

				$result = $db->query("SELECT * FROM v_meetstaat_werf_332 WHERE ID > 272333 AND nummer != '' AND voorziene_HV != '' ORDER BY ID DESC LIMIT 1,1");
				$row= $result->fetchrow(MDB2_FETCHMODE_ASSOC);

				$result = $row["id"];

				print $result;

				return $result;




?>