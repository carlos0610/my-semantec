<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_GET["orden_id"];

        include("conexion.php");
        $sql = "UPDATE ordenes SET 
					estado = 0

        			WHERE ord_id = $ord_id";

	mysql_query($sql);//modificacion de la orden

        echo $sql;
	$_SESSION["ord_id"] = $ord_id;

	mysql_close();
	header("location:lista-ordenes.php");

?>