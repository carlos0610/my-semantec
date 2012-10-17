<?php
        function getListadoFacturasPagadasYno($fav_id){
           $sql = "SELECT f.fav_id,SUM(o.ord_venta) as ord_venta
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
        

        function getFacturasWhitID($fav_id){
           $sql = "select * from factura_venta Where fav_id=$fav_id";  
           return $factura = mysql_query($sql);       
        }

         function getFacturasCodigoWhitID($fav_id){  
           $fila=mysql_fetch_array(getFacturasWhitID($fav_id));
           $codigo=$fila['cod_factura_venta'];
           return $codigo;
         }

?>
