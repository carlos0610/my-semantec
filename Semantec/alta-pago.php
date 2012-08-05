<?php
include("validar.php");
include "conexion.php";
include "funciones.php";

    
        /* Registro de la factura en COBROS */
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        $tipoPago   =  $_POST["comboTipoPago"];
        $usu_id     =  $_SESSION["usu_id"];
        $fav_id     =  $_GET["fav_id"]; 
        $fav_id     = 20;           //modificar 
        $sql        = "INSERT INTO cobros(tipo_pago_id,usu_id,fav_id,fecha,estado) VALUES ($tipoPago,$usu_id,$fav_id,NOW(),1)";
        
        echo "QUERY : ".$sql;
        $result     = mysql_query($sql);

        if (!$result)
            $error = 1;
        else
            $cobro_id = mysql_insert_id ();
     
        /*** Obtenemos datos del pago ***/
        $nro                = $_POST["txtNroOperacion"];
        $banco              = $_POST["comboBanco"];
        $sucursal           = $_POST["txtSucursal"];
        $fechaEmision       = gfecha($_POST["txtFechaEmision"]);
        $fechaVencimiento   = gfecha($_POST["txtFechaVto"]);
        $firmante           = $_POST["txtFirmante"];
        $cuit               = $_POST["txtCuit"];
        $importe            = $_POST["txtImportePago"];
        $cuenta             = $_POST["comboCuenta"];
         
        
        /*** ADJUNTAR ARCHIVO ***/
                    $idFile = -1;
                    if($_FILES['userfile']['size']>0){      //SI ELIGIO UN ARCHIVO
        
                                $fileName = $_FILES['userfile']['name'];
                                $tmpName  = $_FILES['userfile']['tmp_name'];
                                $fileSize = $_FILES['userfile']['size'];
                                $fileType = $_FILES['userfile']['type'];
                                //$error    = $_FILES['userfile']['error'];

                                $fp      = fopen($tmpName, 'r');
                                $content = fread($fp, filesize($tmpName));
                                $content = addslashes($content);
                                fclose($fp);

                    if(!get_magic_quotes_gpc())
                        {
                        $fileName = addslashes($fileName);
                        }
                            $sql_file = "INSERT INTO files (file_name, file_size, file_type, file_content,tabla ) ".
                                        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','cobros')";
                                        $result=mysql_query($sql_file);
                                        if(!$result)
                                            $error=1;
                                        else
                                            $idFile = mysql_insert_id();
                    }                                
        /*** Fin ADJUNTAR ARCHIVO ***/                                
        
        
        
        
      switch ($tipoPago){
          
          /* Efectivo */
          case 1:       if ($idFile == -1)
                        $sql = "INSERT INTO cobros_detalle_pago (cobros_id,importe) VALUES ($cobro_id,$importe)"; 
                        else
                        $sql = "INSERT INTO cobros_detalle_pago (cobros_id,importe,files_id) VALUES ($cobro_id,$importe,$idFile)";    
                          break; 
                      
          /* Cheque pago diferido */
          case 2:        if ($idFile == -1)
                            $sql =  "INSERT INTO cobros_detalle_pago (cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante) VALUES ($cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit')";                     // Sin file
                         else
                             $sql =  "INSERT INTO cobros_detalle_pago (cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante,files_id) VALUES ($cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit',$idFile)";   // Con file
                         break;
          
          /* Transferencia bancaria*/
          case 3:       if ($idFile == -1)
                            $sql = "INSERT INTO cobros_detalle_pago (cobros_id,nro,cuentabanco_id,importe) VALUES ($cobro_id,'$nro',$cuenta,$importe)"; // Sin file           
                        else
                            $sql = "INSERT INTO cobros_detalle_pago (cobros_id,nro,cuentabanco_id,importe,files_id) VALUES ($cobro_id,'$nro',$cuenta,$importe,$idFile)"; // Sin file
                            break;
                        }
                        
                       $result = mysql_query($sql);               
                        echo "<br>QUERY : ".$sql;
                       if (!$result)
                           $error = 1;
                        
                        
    /* RETENCIONES */


    if (isset($_POST["chkGanancias"])){
        $fecha      = gfecha($_POST["txtFecha1"]);
        $prefijo    = $_POST["txtPrefijo"];
        $importe    = $_POST["txtImporte"];
        $nro        = $_POST["txtNro"];

       $sql = "INSERT INTO cobros_detalle_retencion (cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($cobro_id,1,'$fecha','$prefijo','$nro',$importe)"; 
       $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;  
    }
    
    
    if (isset($_POST["chkIva"])){
        $fecha      = gfecha($_POST["txtFecha2"]);
        $prefijo    = $_POST["txtPrefijo2"];
        $importe    = $_POST["txtImporte2"];
        $nro        = $_POST["txtNro2"];
        $idiva      = $_POST["comboIva"];

        $sql = "INSERT INTO cobros_detalle_retencion (idiva,cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($idiva,$cobro_id,2,'$fecha','$prefijo','$nro',$importe)"; 
        $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;
    
        
    }
    
    
    if (isset($_POST["chkIIBB"])){
        $fecha      = gfecha($_POST["txtFecha3"]);
        $prefijo    = $_POST["txtPrefijo3"];
        $importe    = $_POST["txtImporte3"];
        $nro        = $_POST["txtNro3"];

        $sql = "INSERT INTO cobros_detalle_retencion (cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($cobro_id,3,'$fecha','$prefijo','$nro',$importe)"; 
        $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;     
    }
        
  
    if (isset($_POST["chkSUSS"])){
        $fecha      = gfecha($_POST["txtFecha4"]);
        $prefijo    = $_POST["txtPrefijo4"];
        $importe    = $_POST["txtImporte4"];
        $nro        = $_POST["txtNro4"];
        $provincia  = $_POST["comboProvincias"];
        
        $sql = "INSERT INTO cobros_detalle_retencion (provincias_id,cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($provincia,$cobro_id,4,'$fecha','$prefijo','$nro',$importe)";
        $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;            
            
        echo "<br>QUERY : ".$sql;
        
     }
     

     
     if ($error){
         mysql_query("ROLLBACK");
         echo "<br> Error en transacción";
         } else {
     mysql_query("COMMIT");
                }
                
                
?>
