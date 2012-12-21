<?php


function getListarTodoRetenciones(){
            $sql = "SELECT  ret_id, nombre FROM retenciones WHERE estado=1";
            return  $result = mysql_query($sql);
    
}


function getRetencionesByIdAndFecha($id,$fecha_ini,$fecha_fin){
//            $sql =    "SELECT r.nombre,SUM(ret_importe) as suma FROM cobros_detalle_retencion c
//                        INNER JOIN retenciones r
//                        ON r.ret_id = c.ret_id
//                        WHERE c.ret_id = $id
//                        AND ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
    
    $sql = "SELECT c.id,r.nombre,dr.ret_fecha,c.grupo_fav_id,dr.ret_importe,dr.ret_prefijo,dr.ret_codigo FROM retenciones r 
                            INNER JOIN cobros_detalle_retencion dr
                            ON r.ret_id = dr.ret_id
                            INNER JOIN cobros c 
                            ON c.id = dr.cobros_id
                            WHERE ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                            AND r.ret_id = $id    
                            ORDER BY ret_fecha desc";
                           
            return $result  = mysql_query($sql);
    
                                        }


function getRetencionesByFecha($fecha_ini,$fecha_fin){
//            $sql =    "SELECT r.nombre,SUM(ret_importe) as suma FROM cobros_detalle_retencion c
//                        INNER JOIN retenciones r
//                        ON r.ret_id = c.ret_id
//                        AND ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
//                        GROUP BY r.nombre;";
    
              $sql = "SELECT c.id,r.nombre,dr.ret_fecha,c.grupo_fav_id,dr.ret_importe,dr.ret_prefijo,dr.ret_codigo FROM retenciones r 
                            INNER JOIN cobros_detalle_retencion dr
                            ON r.ret_id = dr.ret_id
                            INNER JOIN cobros c 
                            ON c.id = dr.cobros_id
                            WHERE ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                            ORDER BY ret_fecha desc,id";    
            return $result  = mysql_query($sql);
    
                                        }


?>
