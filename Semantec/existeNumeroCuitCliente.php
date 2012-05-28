<?php

include("validar.php");
include("funciones.php");

$cuit= $_GET["usuario"];
$cuit=substr($cuit, 0, 11);
include("conexion.php");
$sql = "SELECT `cli_cuit` FROM `clientes` WHERE `cli_cuit` = $cuit " ;
$result=mysql_query($sql); //alta de la orden


$_SESSION["query"] = $sql;
$ord_id = mysql_insert_id();

$num_rows = mysql_num_rows($result);



$_SESSION["ord_id"] = $ord_id;
mysql_close();


header('Content-type: text/xml');

if ($num_rows>0) {
    echo("<?xml version=\"1.0\" ?><existe>true</existe>");
} else {
    echo("<?xml version=\"1.0\" ?><existe>false</existe>");
}

?>