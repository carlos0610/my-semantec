<?php
include("validar.php");
include "conexion.php";
include "funciones.php";

    
        /* Registro de la factura en COBROS */
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        $fav_id     =  $_GET["fav_id"];
        $ccc_id     =  $_GET["ccc_id"];
        
        /* PAGO DE LA FACTURA */
        // ACTUALIZAMOS LA FECHA DE PAGO EN LA FACTURA
        $sql = "UPDATE factura_venta SET fav_fecha_pago = NOW(), usu_nombre = '$usuario' where fav_id = $fav_id";
        $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;
        
  
        // ACTUALIZAMOS LAS ORDENES A ESTADO FINALIZADO PAGADO
        $sql = "UPDATE ordenes set est_id = 14 where gru_id = (SELECT gru_id from factura_venta where fav_id = $fav_id);";
        $result = mysql_query($sql);
    
        if (!$result)
            $error = 1;
        
        //INSERTAMOS EL PAGO COMO UN DETALLE EN LA CUENTA CORRIENTE 
        $sql = "INSERT into detalle_corriente_cliente (ccc_id,fav_id,estado) VALUES ($ccc_id,$fav_id,1)";
        echo "Query ccc_id: ".$sql;
        $result = mysql_query($sql);
        
        if (!$result)
            $error = 1;
        
        
        
        $usu_id     =  $_SESSION["usu_id"];
         
        $cantTipoPago     =  $_GET["cantTipoPago"]; 
      //  $fav_id     = 20;           //modificar 
        $sql        = "INSERT INTO cobros(usu_id,fav_id,fecha,estado) VALUES ($usu_id,$fav_id,NOW(),1)";
        
        echo "QUERY : ".$sql;
        $result     = mysql_query($sql);

        if (!$result)
            $error = 1; 
        else
            $cobro_id = mysql_insert_id ();
     
        
        
        
        for ($i = 1; $i <= $cantTipoPago; $i++) {
        /*** Obtenemos datos del pago ***/
        $tipoPago               =  $_POST["comboTipoPago$i"];
        $nro                    = $_POST["txtNroOperacion$i"];
        $banco                  = $_POST["comboBanco$i"];
        $sucursal               = $_POST["txtSucursal$i"];
        $fechaEmision           = gfecha($_POST["txtFechaEmision$i"]);
        $fechaVencimiento       = gfecha($_POST["txtFechaVto$i"]);
        $firmante               = $_POST["txtFirmante$i"];
        $cuit                   = $_POST["txtCuit$i"];
        $importe                = $_POST["txtImportePago$i"]; 
        $cuenta                 = $_POST["comboCuenta$i"];
        $fechaTransferencia     = gfecha($_POST["txtFechaTransferencia$i"]);
         
        echo "<br>TIPO PAGO ID".$tipoPago;
        
        /*** ADJUNTAR ARCHIVO ***/
                    $idFile = -1;
                    $userfile="userfile$i";
                    if($_FILES[$userfile]['size']>0){      //SI ELIGIO UN ARCHIVO
        
                                $fileName = $_FILES[$userfile]['name'];
                                $tmpName  = $_FILES[$userfile]['tmp_name'];
                                $fileSize = $_FILES[$userfile]['size'];
                                $fileType = $_FILES[$userfile]['type'];
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
          case 2:       if ($idFile == -1)
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,importe) VALUES ($tipoPago,$cobro_id,$importe)"; 
                        else
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,importe,files_id) VALUES ($tipoPago,$cobro_id,$importe,$idFile)";    
                        
                        //echo "<br>QUERY EFECTIVO ".$sql;
                        
                        break; 
                      
          /* Cheque pago diferido */
          case 1:        if ($idFile == -1)
                            $sql =  "INSERT INTO cobros_detalle_pago (cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante) VALUES ($cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit')";                     // Sin file
                         else
                             $sql =  "INSERT INTO cobros_detalle_pago (cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante,files_id) VALUES ($cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit',$idFile)";   // Con file
                         break;
          
          /* Transferencia bancaria*/
          case 3:       if ($idFile == -1)
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,cuentabanco_id,importe,fecha_transferencia) VALUES ($tipoPago,$cobro_id,'$nro',$cuenta,$importe,'$fechaTransferencia')"; // Sin file           
                        else
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,cuentabanco_id,importe,files_id,fecha_transferencia) VALUES ($tipoPago,$cobro_id,'$nro',$cuenta,$importe,$idFile,'$fechaTransferencia')"; // Sin file
                            break;
                        }
                        
                       $result = mysql_query($sql);               
                        echo "<br>QUERY : ".$sql;
                       if (!$result)
                           $error = 1;
             
                       
        }                
                       
                        
    /* RETENCIONES */


    if (isset($_POST["chkGanancias"])){
        $fecha      = gfecha($_POST["txtFecha1"]);
        $prefijo    = $_POST["txtPrefijo1"];
        $importe    = $_POST["txtImporte1"];
        $nro        = $_POST["txtNro1"];

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
     header("location:lista-facturas.php");
                }
                
                
?>
