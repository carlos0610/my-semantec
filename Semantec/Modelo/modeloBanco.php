<?php
	function getBancoWithId($id){
		 $sql = "SELECT ban_id , ban_nombre FROM `banco` 
                         WHERE ban_id = $id 
                         ORDER BY ban_nombre ";
                 $bancos = mysql_query($sql);
             return    $fila=mysql_fetch_array($bancos);
             
	}
        function getBancoNombre($id){
                 $fila=getBancoWithId($id);
                 return $fila['ban_nombre'] ;
	}
?>
