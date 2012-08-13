<?php


function getListarTodoRetenciones(){
            $sql = "SELECT  ret_id, nombre FROM retenciones WHERE estado=1";
            return  $result = mysql_query($sql);
    
}


function getRetencionesByIdAndFecha($id,$fecha_ini,$fecha_fin){
            $sql =    "SELECT r.nombre,SUM(ret_importe) as suma FROM cobros_detalle_retencion c
                        INNER JOIN retenciones r
                        ON r.ret_id = c.ret_id
                        WHERE c.ret_id = $id
                        AND ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
 
            return $result  = mysql_query($sql);
    
                                        }


function getRetencionesByFecha($fecha_ini,$fecha_fin){
            $sql =    "SELECT r.nombre,SUM(ret_importe) as suma FROM cobros_detalle_retencion c
                        INNER JOIN retenciones r
                        ON r.ret_id = c.ret_id
                        AND ret_fecha BETWEEN '$fecha_ini' AND '$fecha_fin'
                        GROUP BY r.nombre;";
            
            return $result  = mysql_query($sql);
    
                                        }


?>
