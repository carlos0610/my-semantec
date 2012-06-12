<?php

include("validar.php");
include("funciones.php");

$cli_id= $_GET["usuario"];

include("conexion.php");

$sql = "SELECT c.cli_nombre, c.cli_cuit , i.iva_id , c.cli_rubro , c.cli_direccion , c.cli_direccion_fiscal , i.iva_nombre ,
               p.nombre as provincia,
               pa.nombre as partido,
               l.nombre as localidad
        FROM clientes c , iva_tipo i , ubicacion u,provincias p, partidos pa,localidades l
        WHERE  c.cli_id =$cli_id
        AND  c.iva_id = i.iva_id 
        AND c.ubicacion_id = u.id
        AND u.provincias_id = p.id
        AND u.partidos_id = pa.id
	AND u.localidades_id = l.id       
        ";

$result=mysql_query($sql); 

$fila_datos_cliente = mysql_fetch_array($result); 

$senores=$fila_datos_cliente["cli_nombre"];
$cuit=verCUIT($fila_datos_cliente["cli_cuit"]);
$iva_id=$fila_datos_cliente["iva_id"];


mysql_close();


header('Content-type: text/xml');

   
echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
echo "<note>";
echo "<senores>$senores</senores>";
echo "<iva_id>$iva_id</iva_id>";
echo "<cuit>$cuit</cuit>";
echo "</note>";
    
    

?>