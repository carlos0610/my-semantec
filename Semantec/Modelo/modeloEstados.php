<?php

	function getEstadosParaCombo()
        {
	    $sql = "SELECT  est_id, est_nombre, est_color FROM estados where est_id <= 11";
             return       $estados = mysql_query($sql);
             
	}
?>
