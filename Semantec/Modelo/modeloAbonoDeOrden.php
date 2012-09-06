<?php

	function saveAbonoDeOrden($venta,$costo,$usuarioId,$abonoId,$ordId){
		 $sql = "INSERT INTO `abono_de_orden`(`valor_venta`, `valor_costo`, `fecha_alta`, `usuario_alta`,`abono_id`, `ord_id`) 
                        VALUES ($venta,$costo,NOW(),'$usuarioId',$abonoId,$ordId)";ECHO $sql;
                 return  $abono = mysql_query($sql);
             
	}
?>
