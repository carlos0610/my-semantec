<?php
include "validar.php";
include "conexion.php";

$usu_id         = $_POST["usu_id"];
$cli_id         = $_POST["comboPerfil"];
$usu_nombre     = $_POST["txtNombre"];
$usu_login      = $_POST["txtUsuario"];

    



if (isset($_REQUEST['chkCambiarClave'])){
    $usu_clave = $_POST["txtClave"];
    $sql = "UPDATE usuarios_portal SET usu_nombre = '$usu_nombre',usu_login = '$usu_login',usu_pass = md5('$usu_clave'),cli_id = $cli_id WHERE id = $usu_id";
       
} else {
    $sql = "UPDATE usuarios_portal SET usu_nombre = '$usu_nombre',usu_login = '$usu_login',cli_id = $cli_id WHERE id = $usu_id";
    
}

$_SESSION["usu_id"] = $usu_id;

header("location:ver-alta-usuarios-portal.php?action=2");



mysql_query($sql);
mysql_close();






?>
