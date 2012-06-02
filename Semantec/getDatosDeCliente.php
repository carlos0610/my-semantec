<?php

include("validar.php");
include("funciones.php");

$cli_id= $_GET["usuario"];

include("conexion.php");

$sql = "SELECT c.cli_nombre, c.cli_cuit , i.iva_id , c.cli_rubro , c.zon_id , c.cli_direccion , c.cli_direccion_fiscal , i.iva_nombre ,z.zon_nombre
        FROM clientes c , iva_tipo i , zonas z
        WHERE  c.cli_id =$cli_id
        AND  c.iva_id = i.iva_id 
        AND  c.zon_id = z.zon_id
        ";

$result=mysql_query($sql); 

$fila_datos_cliente = mysql_fetch_array($result); 

$senores=$fila_datos_cliente["cli_nombre"];
$domicilio=$fila_datos_cliente["cli_direccion"];
$iva_nombre=$fila_datos_cliente["iva_nombre"];
$zona=$fila_datos_cliente["zon_nombre"];
$cuit=verCUIT($fila_datos_cliente["cli_cuit"]);

mysql_close();


header('Content-type: text/xml');

   
echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
echo "<note>";
echo "<senores>$senores</senores>";
echo "<domicilio>$domicilio</domicilio>";
echo "<iva_nombre>$iva_nombre</iva_nombre>";
echo "<cuit>$cuit</cuit>";
echo "<zona>$zona</zona>";
echo "</note>";
    
    

?>