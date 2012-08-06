<?php

include "conexion.php";
include "validar.php";

$ban_id       = $_POST["ban_id"];
$cut_id       = $_POST["cut_id"];
$nombre       = $_POST["nombre"];
$nro          = $_POST["nro"];
$cue_cbu      = $_POST["cue_cbu"];


$sql = "INSERT INTO `cuentabanco`(`cut_id`, `ban_id`, `nro`, `nombre`, `cbu`)
	VALUES ('$cut_id','$ban_id' ,'$nro','$nombre','$cue_cbu')
        ";

    mysql_query($sql);

    $idUsuario = mysql_insert_id();
    $_SESSION["usu_id"] =  $idUsuario;
		mysql_close();
		header("location:lista-cuentasbanco.php");
    
    






?>
