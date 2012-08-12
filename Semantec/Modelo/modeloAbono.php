<?php
	function getAbonoWithId($id){
		 $sql = "SELECT `id`, `nombre`, `fecha_alta`, `usu_id`, `estado` 
                        FROM `abonos` WHERE `id`= $id
                        ORDER BY nombre";
             return       $abono = mysql_query($sql);
             
	}
        
        function getAbonoNombreWithId($id){
                 $abono = getAbonoWithId($id);
                 $fila = mysql_fetch_array($abono);
             return     $fila['nombre']; 
             
	}
        
        
?>
