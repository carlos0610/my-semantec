<?php

        function updateOrdenesEsAbono($id,$esAbono){
		 $sql = "UPDATE `ordenes` SET 
                        `es_abono`=$esAbono
                         WHERE `ord_id`=$id
                        "; echo ' update:  ',$sql;
           return        $historial = mysql_query($sql);
                  
             
	}
?>
