<?php
include "validar.php";
include "conexion.php";

$usu_id         = $_POST["usu_id"];
$rol_id         = $_POST["comboPerfil"];
$usu_nombre     = $_POST["txtNombre"];
$usu_login      = $_POST["txtUsuario"];
$usu_email      = $_POST["txtEmail"];
    



if (isset($_REQUEST['chkCambiarClave'])){
    $usu_clave = $_POST["txtClave"];
    $sql = "UPDATE usuarios SET usu_nombre = '$usu_nombre',usu_login = '$usu_login',usu_email='$usu_email',usu_clave = '$usu_clave',rol_id = $rol_id WHERE usu_id = $usu_id";
       
} else {
    $sql = "UPDATE usuarios SET usu_nombre = '$usu_nombre',usu_login = '$usu_login',usu_email='$usu_email',rol_id = $rol_id WHERE usu_id = $usu_id";
    
}

$_SESSION["usu_id"] = $usu_id;

header("location:ver-alta-usuarios.php?action=2");



mysql_query($sql);
mysql_close();






?>
