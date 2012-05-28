<?php

include("validar.php");
include("funciones.php");

$cuit= $_GET["usuario"];
$cuit=substr($cuit, 0, 11);

include("conexion.php");
$sql = "SELECT `prv_cuit`  FROM `proveedores` WHERE `prv_cuit` = $cuit " ;
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