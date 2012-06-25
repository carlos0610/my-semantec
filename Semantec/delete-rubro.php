<?php
        include("validar.php");
        include("funciones.php");

        $id = $_GET["id"];

        include("conexion.php");
        $sql = "UPDATE rubros SET 
					estado = 0

        			WHERE `rub_id` = $id";
        echo $sql;
	mysql_query($sql);//modificacion de la orden
	$_SESSION["id"] = $id;

	mysql_close();
	header("location:lista-rubros.php");

?>