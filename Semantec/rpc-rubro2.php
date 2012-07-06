<?php
	
	$db = mysql_connect('190.228.29.195', 'semantec' ,'s3m4nt3c');
		mysql_select_db('semantec', $db);
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $_POST['queryString'];
			
			
			if(strlen($queryString) >0) {
				
				$rs_ax = mysql_query("SELECT prv_rubro FROM proveedores WHERE prv_rubro LIKE '$queryString%' LIMIT 10");
				if($rs_ax) {

					while ($result = mysql_fetch_array($rs_ax)) {

	         			echo '<li onClick="fill(\''.utf8_encode($result["prv_rubro"]).'\');">'.utf8_encode($result["prv_rubro"]).'</li>';
	         		}
				} else {
					echo 'ERROR: There was a problem with the query.';
				}
			} else {

			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>