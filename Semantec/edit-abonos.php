<?php
include("validar.php");
include "conexion.php";
include "funciones.php";
include("Modelo/modeloClientes.php");

    
        /* Registro de la factura en COBROS */
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        $abono_id     =  $_GET["idAbono"];
        $cantidadAbonos     =  $_GET["cantidadAbonos"];
        $nombreAbono=$_POST['nombre_abono'];
        $usu_id     =  $_SESSION["usu_id"];

        $sql        = "UPDATE `abonos` SET `nombre`='$nombreAbono' 
                        WHERE id= $abono_id";
        echo "QUERY : ".$sql;
        $result     = mysql_query($sql);

        if (!$result)
            $error = 1; 

         // agrego los abonos de cada sucursal
        for ($i = 1; $i <= $cantidadAbonos; $i++) {
        /*** Obtenemos datos del pago ***/
        $abono_valor_visita           =  $_POST["abono_valor_visita$i"];
        $abono_costo                    = $_POST["abono_costo$i"];
        $abono_venta                 = $_POST["abono_venta$i"];
        $abono_cli             = $_POST["abono_cli$i"];
        $abono_idDetalle             = $_POST["abono_idDetalle$i"];

       $sql = "UPDATE `abonos_detalle` 
                SET  `valor_costo`=$abono_costo,`valor_venta`=$abono_venta,`valor_visita`= 1
                WHERE `id`=$abono_idDetalle";
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