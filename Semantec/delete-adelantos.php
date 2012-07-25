<?php

include "conexion.php";
$ord_det_id   = $_GET["ord_det_id"];
session_start();
mysql_query("BEGIN"); // Inicio de Transacción
$error = false;

/* Obtengo datos con el detalle_id para luego auditarlos como un nuevo orden detalle*/
$sql = "SELECT ord_id,nombre_estado,ord_det_monto FROM ordenes_detalle WHERE ord_det_id = $ord_det_id";
$datos_orden = mysql_query($sql);
$fila_datos_orden = mysql_fetch_array($datos_orden);





$sql = "UPDATE ordenes_detalle SET ord_det_monto = 0 WHERE ord_det_id = $ord_det_id";
$result = mysql_query($sql);
if (!$result)
    $error = true;

/* Recupero los datos de la primera consulta para insertarlos en un nuevo detalle */
$ord_id         =   $fila_datos_orden["ord_id"];
$nombre_estado  =   $fila_datos_orden["nombre_estado"];
$ord_det_monto  =   $fila_datos_orden["ord_det_monto"];
$usuario        =   $_SESSION["usu_nombre"];


$sql = "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado) VALUES ('Se cancela el adelanto #$ord_det_id de $ord_det_monto pesos',0,$ord_id,NOW(),'$usuario',1,'$nombre_estado')";
$result = mysql_query($sql);
echo "INSERT: ".$sql;
if(!$result)
    $error = true;



                              if($error) 
                                {
                                mysql_query("ROLLBACK");
                                echo "Error en la transaccion";
                                mysql_close();
                          
                                } 
                                else 
                                {
                                mysql_query("COMMIT");
                                mysql_close();
                                echo "Transacción exitosa";
                                header("location:lista-req-ordenes.php?orden=$ord_id&action=2");
                        }


    








?>
