<?php

	function getAbonosDetalleWithAbonoId($id){
		 $sql = "SELECT `id`, `abonos_id`, `cli_id`, `valor_costo`, `valor_venta`, `valor_visita`, `usu_id_baja`, `estado` 
                        FROM `abonos_detalle` 
                        WHERE `abonos_id`= $id
                        ORDER BY abonos_id";
                 return  $abono = mysql_query($sql);
             
	}
?>
