<?php

	function saveAbonoDeOrden($venta,$costo,$usuarioId,$abonoId,$ordId){
		 $sql = "INSERT INTO `abono_de_orden`(`valor_venta`, `valor_costo`, `fecha_alta`, `usuario_alta`,`abono_id`, `ord_id`) 
                        VALUES ($venta,$costo,NOW(),'$usuarioId',$abonoId,$ordId)";
                 return  $abono = mysql_query($sql);
             
	}
        
               function getAbonosDeOrdenWithCliId($ord_id){
		 $sql = "SELECT idabono_de_orden,valor_venta,valor_costo,fecha_alta,usuario_alta,estado,abono_id,ord_id
                        FROM `abono_de_orden` 
                        WHERE `ord_id`= $ord_id
                        AND estado=1
                        ORDER BY fecha_alta DESC"; 
                 return  $abono = mysql_query($sql);
             
	}
?>
