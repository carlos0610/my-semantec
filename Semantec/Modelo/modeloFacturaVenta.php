<?php
        function getListadoFacturasPagadasYno($fav_id){
           $sql = "SELECT f.fav_id,ROUND(SUM(o.ord_venta)*1.21,2) as ord_venta
                FROM ordenes o,cuentacorriente_cliente cc ,factura_venta f,grupo_ordenes g_o
                WHERE cc.cli_id = o.cli_id 
                AND f.fav_id = $fav_id
                AND o.estado = 1 
                AND cc.estado = 1 
                AND o.est_id >= 11
                AND f.estado = 1
                AND g_o.gru_id = f.gru_id
                AND g_o.gru_id = o.gru_id";
           
                  mysql_query($sql);
           return $factura = mysql_query($sql);    
            
        }
           function getListadoFacturasWithGrupo($fav_id){
            $sql = "SELECT f.fav_id , f.cod_factura_venta FROM factura_venta f  WHERE grupo_fac_pago = $fav_id AND estado =1
               ;";
           
                  mysql_query($sql); 
           return $factura = mysql_query($sql);   
            
        }

        function getFacturasWhitID($fav_id){
           $sql = "select * from factura_venta Where fav_id=$fav_id";  
           return $factura = mysql_query($sql);       
        }

         function getFacturasCodigoWhitID($fav_id){  
           $fila=mysql_fetch_array(getFacturasWhitID($fav_id));
           $codigo=$fila['cod_factura_venta'];
           return $codigo;
         }
         
         function getTotalAPagarConIva($fav_id){
             $sql = "SELECT fav_id,ROUND(SUM(det_fav_precio)*1.21,2) AS total FROM detalle_factura_venta WHERE fav_id = $fav_id;";
             return $factura = mysql_query($sql);       
         }
         
         
         function getFacturasSinPagarPorClienteId($cli_id){
             $sql = "SELECT fv.fav_id,fv.cod_factura_venta,IFNULL(fv.grupo_fac_pago,'-') as grupo_fac_pago,IFNULL(fv.fav_fecha_pago,'-') as fecha_pago,fv.fav_fecha,SUM(dfv.det_fav_precio) as total,IFNULL(nc.nrc_id,'-') as nrc_id,IFNULL(dnc.det_nrc_precio,0) as total_nota 
                FROM factura_venta fv
                INNER JOIN detalle_factura_venta dfv ON dfv.fav_id = fv.fav_id
                LEFT JOIN nota_credito nc ON nc.gfn_id = fv.grupo_nota_credito
                LEFT JOIN detalle_nota_credito dnc ON  nc.nrc_id = dnc.nrc_id 
                WHERE fv.gru_id in 
                                    (SELECT DISTINCT(go.gru_id) FROM grupo_ordenes go
                                    INNER JOIN ordenes o
                                    ON go.gru_id = o.gru_id
                                    WHERE o.cli_id in (SELECT cli_id from clientes where sucursal_id = $cli_id or cli_id = $cli_id and estado = 1)
                                    AND o.estado = 1
                                    )
                AND fv.fav_fecha_pago is null                    
                group by fav_id";   
             
                return $factura = mysql_query($sql);
             
         }
         
            function getFacturasPagasUsanNCPorClienteId($cli_id){
             $sql = "SELECT fv.fav_id,fv.cod_factura_venta,IFNULL(fv.grupo_fac_pago,'-') as grupo_fac_pago,IFNULL(fv.fav_fecha_pago,'-') as fecha_pago,fv.fav_fecha,SUM(dfv.det_fav_precio) as total,IFNULL(nc.nrc_id,'-') as nrc_id,IFNULL(dnc.det_nrc_precio,0) as total_nota 
                FROM factura_venta fv
                INNER JOIN detalle_factura_venta dfv ON dfv.fav_id = fv.fav_id
                LEFT JOIN nota_credito nc ON nc.gfn_id = fv.grupo_nota_credito
                LEFT JOIN detalle_nota_credito dnc ON  nc.nrc_id = dnc.nrc_id 
                WHERE fv.gru_id in 
                                    (SELECT DISTINCT(go.gru_id) FROM grupo_ordenes go
                                    INNER JOIN ordenes o
                                    ON go.gru_id = o.gru_id
                                    WHERE o.cli_id in (SELECT cli_id from clientes where sucursal_id = $cli_id or cli_id = $cli_id and estado = 1)
                                    AND o.estado = 1
                                    )
                AND fv.nota_credito_id is not null                    
                group by fav_id";
             
                return $factura = mysql_query($sql);
             
         }
         
        function getFacturasGrupoNota_CreditoWhitID($fav_id){
           $sql = "select grupo_nota_credito from factura_venta Where fav_id =$fav_id ";  
           return $factura = mysql_query($sql);       
        }

?>
