<?php
include("validar.php");
include "conexion.php";
include "funciones.php";

    
        /* Registro de la factura en COBROS */
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        $cantidadAbonos     =  $_GET["cantidadAbonos"];
        $nombreAbono=$_POST['nombre_abono'];
        $usu_id     =  $_SESSION["usu_id"];

        $sql        = "INSERT INTO `abonos`( `nombre`, `fecha_alta`, `usu_id`) VALUES ('$nombreAbono',NOW(),$usu_id)";
        echo "QUERY : ".$sql;
        $result     = mysql_query($sql);

        if (!$result)
            $error = 1; 
        else
            $abono_id = mysql_insert_id ();
         // agrego los abonos de cada sucursal
        for ($i = 1; $i <= $cantidadAbonos; $i++) {
        /*** Obtenemos datos del pago ***/
        $abono_valor_visita           =  $_POST["abono_valor_visita$i"];
        $abono_costo                    = $_POST["abono_costo$i"];
        $abono_venta                 = $_POST["abono_venta$i"];
        $abono_cli             = $_POST["abono_cli$i"];
        
       $sql = "INSERT INTO `abonos_detalle`( `abonos_id`, `cli_id`, `valor_costo`, `valor_venta`, `valor_visita`) 
                                    VALUES ($abono_id,$abono_cli,$abono_costo,$abono_venta,$abono_valor_visita)";
        $result = mysql_query($sql);               
               echo "<br>QUERY : ".$sql;
          if (!$result)
                $error = 1;
             
                       
        }                
   
     if ($error){
         mysql_query("ROLLBACK");
         echo "<br> Error en transacción";
         } else {
     mysql_query("COMMIT");
     header("location:ver-abono.php?idAbono=$abono_id");
                }
                
                
?>
