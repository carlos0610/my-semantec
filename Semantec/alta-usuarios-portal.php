<?php

include "conexion.php";
include "validar.php";

$nombre     = $_POST["txtNombre"];
$usuario    = $_POST["txtUsuario"];
$clave      = $_POST["txtClave"];
$cli_id     = $_POST["comboPerfil"];



    $sql = "INSERT INTO usuarios_portal (cli_id, usu_login, usu_pass, usu_nombre)
	VALUES ($cli_id,'$usuario' ,'$clave' , '$nombre')";

    mysql_query($sql);

    $idUsuario = mysql_insert_id();
    $_SESSION["usu_id"] =  $idUsuario;
    
    echo "EL ULTIMO USUARIO INGRESADO ES ".$idUsuario;
    echo "<br> QUERY ".$sql;
    
    header("location:ver-alta-usuarios-portal.php?action=1");






?>
