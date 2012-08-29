<?php

        function updateOrdenesEsAbono($id,$esAbono){
		 $sql = "UPDATE `ordenes` SET 
                        `es_abono`=$esAbono
                         WHERE `ord_id`=$id
                        "; echo ' update:  ',$sql;
           return        $historial = mysql_query($sql);
                  
             
	}
        
        function getOrdenesWithCliId($id){ //no usado aun
		 $sql = "SELECT `ord_id`, `usu_id`, `gru_id`, `ord_codigo`, `ord_descripcion`, `cli_id`, `prv_id`, `est_id`, `ord_alta`, `ord_plazo_proveedor`, `ord_plazo`, `ord_costo`, `ord_venta`, `estado`, `gru_id_compra`, `es_abono` 
                        FROM `ordenes` 
                        WHERE `ord_codigo`= $id
                        AND estado=1
                        ORDER BY ord_id";  
                 return  $abono = mysql_query($sql);
             
	}
        
        function getOrden_esAbonoWithCliId($id){//no usado aun prbar test
                 $abono = getOrdenesWithCliId($id);
                 $fila = mysql_fetch_array($abono);
             return     $fila['es_abono']; 
             
	}
        
        
?>
