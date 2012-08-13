<?php

function getDetalleRetencionByFavId($fav_id){
    
    $sql = "SELECT i.valor,p.nombre as provincia,p.jurisdiccion,c.cobros_id,c.ret_id,r.nombre,c.ret_fecha,c.ret_prefijo,c.ret_codigo,c.ret_importe 
            FROM cobros_detalle_retencion c 
            LEFT JOIN provincias p 
            ON c.provincias_id = p.id
            LEFT JOIN iva i
            ON c.idiva = i.idiva
            INNER JOIN retenciones r
            ON c.ret_id = r.ret_id
            WHERE cobros_id = (SELECT id FROM cobros WHERE fav_id = $fav_id)";
    //echo "QUERY : ".$sql;
    return $detallesRetencion = mysql_query($sql);
    
}




?>
