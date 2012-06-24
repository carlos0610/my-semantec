<?php

include "conexion.php";
include "validar.php";

$nombre     = $_POST["txtNombre"];
$usuario    = $_POST["txtUsuario"];
$clave      = md5($_POST["txtClave"]);
$rol_id     = $_POST["comboPerfil"];
$email      = $_POST["txtEmail"];


$sql = "INSERT INTO usuarios (rol_id, usu_login, usu_clave, usu_nombre, usu_email)
	VALUES ($rol_id,'$usuario' ,'$clave' , '$nombre', '$email')";

mysql_query($sql);

    $idUsuario = mysql_insert_id();
    $_SESSION["usu_id"] =  $idUsuario;
    
    echo "EL ULTIMO USUARIO INGRESADO ES ".$idUsuario;
    echo "<br> QUERY ".$sql;






?>
