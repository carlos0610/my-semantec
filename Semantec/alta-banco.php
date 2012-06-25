<?php

include "conexion.php";
include "validar.php";

$ban_nombre     = $_POST["ban_nombre"];
$ban_telefono    = $_POST["ban_telefono"];
$ban_direccion     = $_POST["ban_direccion"];


$sql = "INSERT INTO banco (ban_nombre, ban_telefono, ban_direccion,estado)
	VALUES ('$ban_nombre','$ban_telefono' ,'$ban_direccion',1)";

mysql_query($sql);

    $idUsuario = mysql_insert_id();
    $_SESSION["usu_id"] =  $idUsuario;
		mysql_close();
		header("location:lista-bancos.php");






?>
