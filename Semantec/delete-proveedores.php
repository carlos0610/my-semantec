<?php
        include("validar.php");
        include("funciones.php");

        $prv_id = $_GET["prv_id"];
        // setea el id del proovedor sin asignar.
        $IdDelProveedorSinAsignar=1;
        
        include("conexion.php");
        $sql = "UPDATE proveedores SET 
				estado = 0
        			WHERE prv_id = $prv_id";
	mysql_query($sql);
        echo $sql;
        
	$_SESSION["prv_id"] = $prv_id;
        
        $sql = "UPDATE ordenes SET 
                                prv_id = $IdDelProveedorSinAsignar
        			WHERE prv_id = $prv_id";
	mysql_query($sql);
        echo $sql;
        
        
	mysql_close();
	header("location:lista-proveedores.php");

?>
