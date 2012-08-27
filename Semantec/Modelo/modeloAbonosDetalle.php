<?php

	function getAbonosDetalleWithAbonoId($id){
		 $sql = "SELECT `id`, `abonos_id`, `cli_id`, `valor_costo`, `valor_venta`, `valor_visita`, `usu_id_baja`, `estado` 
                        FROM `abonos_detalle` 
                        WHERE `abonos_id`= $id
                        ORDER BY abonos_id";
                 return  $abono = mysql_query($sql);
             
	}
        
        
       function getAbonosDetalleWithCliId($id){
		 $sql = "SELECT `id`, `abonos_id`, `cli_id`, `valor_costo`, `valor_venta`, `valor_visita`, `usu_id_baja`, `estado` 
                        FROM `abonos_detalle` 
                        WHERE `cli_id`= $id
                        AND estado=1
                        ORDER BY abonos_id";  
                 return  $abono = mysql_query($sql);
             
	}
        
       function cantAbonoDetalle_AbonoIdWithCliId($id){
                 $abonos = getAbonosDetalleWithCliId($id); 
                 $fila = mysql_num_rows($abonos);
             return     $fila;
             
	}
       function getAbonoDetalle_AbonoIdWithCliId($id){
                 $abono = getAbonosDetalleWithCliId($id);
                 $fila = mysql_fetch_array($abono);
             return     $fila['abonos_id']; 
             
	}
        
       function getAbonoDetalle_ValorCostoWithCliId($id){
            // NO FUNCA
           		 $sql = "SELECT `id`, `abonos_id`, `cli_id`, `valor_costo`, `valor_venta`, `valor_visita`, `usu_id_baja`, `estado` 
                        FROM `abonos_detalle` 
                        WHERE `cli_id`= $id
                        AND estado=1
                        ORDER BY abonos_id"; echo $sql;
                 $abono2 = mysql_query($sql);
                 $valorCosto=0.00;
                 if ($abono2) {
                       $fila = mysql_fetch_array($abono2);
                        $valorCosto=$fila['valor_costo'];  
                 }
                 return     $valorCosto;
             
	}
        
?>
