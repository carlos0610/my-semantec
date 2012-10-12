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
        
        function getOrden_esAbonoWithOrdId($id){//no usado aun prbar test
                 $abono = getOrdenWithOrdId($id);
                 $fila = mysql_fetch_array($abono);
             return     $fila['es_abono']; 
             
	}
        
        function getOrdenWithOrdId($id){ //no usado aun
		 $sql = "SELECT `ord_id`, `usu_id`, `gru_id`, `ord_codigo`, `ord_descripcion`, `cli_id`, `prv_id`, `est_id`, `ord_alta`, `ord_plazo_proveedor`, `ord_plazo`, `ord_costo`, `ord_venta`, `estado`, `gru_id_compra`, `es_abono` 
                        FROM `ordenes` 
                        WHERE `ord_id`= $id
                        AND estado=1
                        ORDER BY ord_id";  
                 return  $abono = mysql_query($sql);
             
	}
        
        
        function getOrdenesWithFavId($id){
		 $sql = "SELECT c.cli_nombre,c.cli_direccion, o.ord_codigo, o.rub_id, o.ord_descripcion, o.ord_venta, o.presupuesto, i.iva_nombre,c.cli_cuit ,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,  c.cli_direccion_fiscal,c.sucursal_id
                FROM ordenes o,clientes c,iva_tipo i,factura_venta f,grupo_ordenes g_o , ubicacion u,provincias p, partidos pa,localidades l
                WHERE 
                f.fav_id 	= $id
                and f.gru_id    = g_o.gru_id
                and g_o.gru_id = o.gru_id
                and o.cli_id = c.cli_id
                AND c.ubicacion_id = u.id
                AND u.provincias_id = p.id
                AND u.partidos_id = pa.id
                AND u.localidades_id = l.id
                and c.iva_id = i.iva_id
                order by provincia
                "; 
                 return   mysql_query($sql);
             
	}
        
?>
