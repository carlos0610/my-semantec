<?php
include "validar.php";
include "conexion.php";
include "funciones.php";


$ord_codigo = $_GET["orden"];
$monto_adelanto = $_GET["adelanto"];
$descripcion = $_GET["desc"];
$usuario = $_SESSION["usu_nombre"];
$pagina  = $_GET["pagina"];


/* OBTENEMOS EL ESTADO EN QUE ESTÁ LA ORDEN */
$sql = "select o.ord_id,e.est_nombre,o.prv_id from ordenes o,estados e 
        where ord_codigo = '$ord_codigo'
        AND o.est_id = e.est_id";

$resultado = mysql_query($sql);
$fila = mysql_fetch_array($resultado);
$estado_nombre = $fila["est_nombre"];
$prv_id = $fila["prv_id"];
$ord_id = $fila["ord_id"];


$sql ="INSERT INTO ordenes_detalle (ord_det_descripcion, ord_det_monto, ord_id, ord_det_fecha, usu_nombre, estado, nombre_estado)
	VALUES ('$descripcion', $monto_adelanto, $ord_id, NOW(), '$usuario', 1, '$estado_nombre')";



mysql_query($sql);

mysql_close();

header("location:ver-corriente-proveedor.php?action=2&prv_id=$prv_id&pagina=$pagina");





?>
