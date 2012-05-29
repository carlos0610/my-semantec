<?php
        include("validar.php");
        include("funciones.php");

        $fav_id = $_GET["fav_id"];

        include("conexion.php");
        $sql = "UPDATE factura_venta SET 
					estado = 0

        			WHERE fav_id = $fav_id";

	mysql_query($sql);//modificacion de la orden

        echo $sql;
	$_SESSION["fav_id"] = $fav_id;

	mysql_close();
	header("location:lista-facturas.php");

?>