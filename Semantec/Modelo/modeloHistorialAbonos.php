<?php

  	function cantHistorialAbonosWithAbonoIdAndFecha($id,$fecha){
		 $sql = "SELECT `idhistorial_abonos`, `abonos_detalle_id`, `ordenes_ord_id`, `fecha_registro` 
                         FROM `historial_abonos` 
                         WHERE `abonos_detalle_id`=$id
                         AND `fecha_registro`='$fecha'
                         AND estado= 1 
                        ";
                   $historial = mysql_query($sql); 
                   $fila = mysql_num_rows($historial); 
             return     $fila;
             
	}
        
        function saveHistorialAbonos($idAbonodetalle,$ordenId,$fecha){
		 $sql = "INSERT INTO `historial_abonos`( `abonos_detalle_id`, `ordenes_ord_id`, `fecha_registro`) 
                         VALUES ($idAbonodetalle,$ordenId,'$fecha')
                        "; 
           return        $historial = mysql_query($sql);
                  
             
	}
        
       function deleteHistorialAbonosWithOrdenId($ordenId){
		 $sql = "UPDATE `historial_abonos` SET 
                        estado=0
                         WHERE `ordenes_ord_id`= $ordenId
                        "; 
             
           return        $historial = mysql_query($sql);
                  
             
	}
        
        function getHistorialAbonos_FechaRegistroWithOrdenId($ordenes_ord_id){
		 $sql = "SELECT `idhistorial_abonos`, `abonos_detalle_id`, `ordenes_ord_id`, `fecha_registro` 
                         FROM `historial_abonos` 
                         WHERE `ordenes_ord_id`=$ordenes_ord_id
                         AND estado= 1 
                        ";
                   $historial = mysql_query($sql);  
                   $fila = mysql_fetch_array($historial); 
             return    $fila["fecha_registro"];
             
	}
?>
