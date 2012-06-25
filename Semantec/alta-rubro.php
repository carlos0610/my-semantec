<?php

include "conexion.php";
include "validar.php";

$rub_nombre     = $_POST["rub_nombre"];

$sql = "INSERT INTO rubros (rub_nombre , estado)
	VALUES ('$rub_nombre',1)";

mysql_query($sql);

    $idUsuario = mysql_insert_id();
    $_SESSION["usu_id"] =  $idUsuario;
		mysql_close();
		header("location:lista-rubros.php");






?>
