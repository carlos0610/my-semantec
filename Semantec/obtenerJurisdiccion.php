<?php

include("validar.php");
include("funciones.php");


$codigo_orden= $_GET["usuario"];

include("conexion.php");
    $sql = "SELECT id,nombre,jurisdiccion FROM provincias WHERE id=$codigo_orden";
$result=mysql_query($sql); //alta de la orden
$result1=mysql_query($sql); //alta de la orden

$_SESSION["query"] = $sql;

$num_rows = mysql_num_rows($result1);

$fila = mysql_fetch_array($result);
$jurisdiccion=$fila['jurisdiccion'];
mysql_close();


header('Content-type: text/xml');

if ($num_rows>0) {
    echo("<?xml version=\"1.0\" ?><existe>$jurisdiccion</existe>");
} else {
    echo("<?xml version=\"1.0\" ?><existe>No registrada</existe>");
}








//header("location:ver-alta-ordenes.php?action=1");
?>