<?php

	function getUsuarioWithId($id){
		 $sql = "SELECT `usu_id`, `rol_id`, `usu_login`, `usu_clave`, `usu_nombre`, `usu_email`, `estado` 
                        FROM `usuarios` 
                        WHERE `usu_id` = $id
                        ORDER BY usu_nombre";
             return       $abono = mysql_query($sql);
             
	}
        
        function getUsuarioNombreWithId($id){
                 $abono = getUsuarioWithId($id);
                 $fila = mysql_fetch_array($abono);
             return     $fila['usu_nombre']; 
             
	}
?>
