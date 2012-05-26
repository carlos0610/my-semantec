<?php

include("validar.php");
include("funciones.php");

$ord_codigo = $_POST["ord_codigo"];

$usu_nombre = $_SESSION["usu_nombre"];
$est_nombre = $_POST["est_nombre"];

$codigo_orden= $_GET["usuario"];

include("conexion.php");
$sql = "SELECT `ord_codigo` FROM `ordenes` WHERE `ord_codigo` = $codigo_orden " ;
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








//header("location:ver-alta-ordenes.php?action=1");
?>