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
        $usu_id     =  $_SESSION["usu_id"];

        $sql        = "UPDATE `abonos` SET `estado`=0
                        WHERE id= $abono_id";
        echo "QUERY : ".$sql;
        $result     = mysql_query($sql);

        if (!$result)
            $error = 1; 


       $sql = "UPDATE `abonos_detalle` 
                SET  `usu_id_baja` =$usu_id,`estado`=0
                WHERE `abonos_id`=$abono_id";
        $result = mysql_query($sql);               
               echo "<br>QUERY : ".$sql;
          if (!$result)
                $error = 1;
             
       $sql = "UPDATE `clientes` SET `id_abono`=NULL WHERE `id_abono`=$abono_id";
        $result = mysql_query($sql);               
               echo "<br>QUERY : ".$sql;
          if (!$result)
                $error = 1;              
                        
  
     if ($error){
         mysql_query("ROLLBACK");
         echo "<br> Error en transacción";
         } else {
     mysql_query("COMMIT");
     header("location:lista-abonos.php");
                }
                
                
?>