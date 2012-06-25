<?php
        include("validar.php");
        include("funciones.php");

        $id = $_GET["id"];

        include("conexion.php");
        $sql = "UPDATE banco SET 
					estado = 0

        			WHERE `ban_id` = $id";

	mysql_query($sql);//modificacion de la orden
        echo $sql;
	$_SESSION["id"] = $id;

	mysql_close();
	header("location:lista-bancos.php");

?>