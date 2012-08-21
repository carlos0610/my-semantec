<?php

  	function cantHistorialAbonosWithAbonoIdAndFecha($id,$fecha){
		 $sql = "SELECT `idhistorial_abonos`, `abonos_detalle_id`, `ordenes_ord_id`, `fecha_registro` 
                         FROM `historial_abonos` 
                         WHERE `abonos_detalle_id`=$id
                         AND `fecha_registro`='$fecha'
                        ";
                   $historial = mysql_query($sql); echo $sql;
                   $fila = mysql_num_rows($historial); echo ' cantidad abonos con id y fecha: ',$fila;
             return     $fila;
             
	}
        
        function saveHistorialAbonos($idAbonodetalle,$ordenId,$fecha){
		 $sql = "INSERT INTO `historial_abonos`( `abonos_detalle_id`, `ordenes_ord_id`, `fecha_registro`) 
                         VALUES ($idAbonodetalle,$ordenId,'$fecha')
                        "; echo ' save:  ',$sql;
           return        $historial = mysql_query($sql);
                  
             
	}

?>
