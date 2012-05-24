<?php
        include("validar.php");
        include("funciones.php");

        $prv_id = $_GET["prv_id"];

        include("conexion.php");
        $sql = "UPDATE proveedores SET 
					estado = 0

        			WHERE prv_id = $prv_id";

	mysql_query($sql);

        echo $sql;
	$_SESSION["prv_id"] = $prv_id;

	mysql_close();
	header("location:lista-proveedores.php");

?>
