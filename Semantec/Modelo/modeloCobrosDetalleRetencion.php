<?php

function getDetalleRetencionByFavId($fav_id){
    
    $sql = "SELECT * FROM cobros_detalle_retencion where cobros_id = (SELECT id FROM cobros WHERE fav_id = $fav_id)";
    echo "QUERY: ".$sql;
    return $detallesRetencion = mysql_query($sql);
    
}




?>
