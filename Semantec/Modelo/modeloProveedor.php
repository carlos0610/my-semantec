<?php
    
        function getProveedorNombreWithId($id){
           $sql = "SELECT prv_nombre
           FROM proveedores
           WHERE 
 	   prv_id = $id
           AND estado = 1
           "; 
           $Clientes = mysql_query($sql);
            $fila = mysql_fetch_array($Clientes); 
            $nombrecompleto=$fila["prv_nombre"];
            return $nombrecompleto;
            }
            
?>
