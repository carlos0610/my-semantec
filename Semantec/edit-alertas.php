<?php

include "validar.php";
include "conexion.php";
//$alerta_prov = $_POST["alerta1"];
//$alerta_venc_orden = $_POST["alerta2"];

$valor_alerta1 = $_POST["alerta_prov"];
$valor_alerta2 = $_POST["alerta_venc"];
$valor_alerta3 = $_POST["alerta_ot"];

/*Modificar esto que esta todo hardcoded, hacerlo dinamico */

$sql = "UPDATE parametros SET valor = $valor_alerta1 WHERE par_id = 1";
mysql_query($sql);

$sql = "UPDATE parametros SET valor = $valor_alerta2 WHERE par_id = 2";
mysql_query($sql);

$sql = "UPDATE parametros SET valor = $valor_alerta3 WHERE par_id = 3";
mysql_query($sql);


mysql_close();


header("location:ver-alta-alertas.php");


?>
