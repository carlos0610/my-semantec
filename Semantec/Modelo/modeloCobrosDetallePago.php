<?php
    function getDetallePagoByFavId($fav_id){
        
        $sql = "SELECT c.tipo_pago_id,t.nombre as pago,c.cobros_id,c.nro,b.ban_nombre,sucursal,cu.nombre,files_id,importe,fecha_emision,fecha_vto,fecha_transferencia,firmante,cuit_firmante 
                FROM cobros_detalle_pago c
                INNER JOIN tipo_pago t
                ON c.tipo_pago_id = t.id
                LEFT JOIN banco b
                ON c.ban_id = b.ban_id
                LEFT JOIN cuentabanco cu
                ON c.cuentabanco_id = cu.id
                WHERE cobros_id = (SELECT id FROM cobros WHERE fav_id = $fav_id)";
        
                return $detallesPago = mysql_query($sql);
        
    }

?>
