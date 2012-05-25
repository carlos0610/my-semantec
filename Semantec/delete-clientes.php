<?php
        include("validar.php");
        include("funciones.php");

        $cli_id = $_GET["cli_id"];

        include("conexion.php");
        $sql = "UPDATE clientes SET 
					estado = 0

        			WHERE cli_id = $cli_id";

	mysql_query($sql);//modificacion de la orden

        echo $sql;
	$_SESSION["cli_id"] = $cli_id;
	mysql_close();
	header("location:lista-clientes.php");

?>
