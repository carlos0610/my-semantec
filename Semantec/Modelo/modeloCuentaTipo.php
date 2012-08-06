<?php
	function getCuentaTipoWithId($id){
		 $sql = "select cut_id,cut_nombre 
                         FROM cuentatipo 
                         WHERE cut_id=$id 
                         ORDER BY cut_nombre ";
                 $tipocuenta = mysql_query($sql);
                return $fila=mysql_fetch_array($tipocuenta);
               
	}
        function getCuentaTipoNombre($id){
                 $fila=getCuentaTipoWithId($id);
                 return $fila['cut_nombre'] ;
	}
?>
