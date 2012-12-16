<?php
include("validar.php");
include "conexion.php";
include "funciones.php";

    
        /* Registro de la factura en COBROS */
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        $cantTotalFav     =  $_GET["cant"];
        $ccc_id     =  $_GET["ccc_id"];
        $cantIIBB    =  $_GET["cantIIBB"];
        $usu_id     =  $_SESSION["usu_id"];
        $cli_id     =  $_GET["cli_id"];
        $totalFavs     =  $_GET["totalFavs"];
         $usuario      =     $_SESSION["usu_nombre"];
        /* PAGO DE LA FACTURA */
         
        // CREO EL GRUPO DE FACTURAS 
        $sql_grupo_fav= "INSERT INTO `grupo_fac_venta`(`gru_fav_fecha_alta`) VALUES (NOW()) ";
        $result=mysql_query($sql_grupo_fav);
        if(!$result){
                     $error=1;echo "error en creacion de grupo de facturas: $sql_grupo_fav";}
        $id_grupo_fav = mysql_insert_id(); 
         
         
        // ACTUALIZAMOS LA FECHA DE PAGO EN CADA FACTURA 
        $i=0; 
         while ($i < $cantTotalFav) 
{   
    $i++; //echo $i,'---';
    $fav_id=$_POST["ofav$i"];


        $sql = "UPDATE factura_venta SET fav_fecha_pago = NOW(), usu_nombre = '$usuario', grupo_fac_pago= '$id_grupo_fav' where fav_id = $fav_id";
        $result = mysql_query($sql);
        
        if (!$result){
            $error = 1;echo "fallo en set de facura venta : $sql <br>";
        }
  
        // ACTUALIZAMOS LAS ORDENES A ESTADO FINALIZADO PAGADO
        $sql = "UPDATE ordenes set est_id = 14 where gru_id = (SELECT gru_id from factura_venta where fav_id = $fav_id);";
        $result = mysql_query($sql);
    
        if (!$result){
            $error = 1;echo 'fallo2';
        }
        //INSERTAMOS EL PAGO COMO UN DETALLE EN LA CUENTA CORRIENTE 
        $sql = "INSERT into detalle_corriente_cliente (ccc_id,fav_id,estado) VALUES ($ccc_id,$fav_id,1)";
        $result = mysql_query($sql);
        
        if (!$result){
            $error = 1; echo 'fallo3';         echo "Query ccc_id: ".$sql;
        }
        }
        
        
       //-----------------------------CAMBIAR EL FAV ID POR EL GRUPO------------------------------------------------  
        
      //  $fav_id     = 20;           //modificar 
        $sql        = "INSERT INTO cobros(usu_id,grupo_fav_id,fecha,estado,cli_id,monto_total) VALUES ($usu_id,$id_grupo_fav,NOW(),1,$cli_id,$totalFavs)";    
        $result     = mysql_query($sql);
        if (!$result){
            $error = 1; echo 'fallo4';  echo "QUERY : ".$sql;
        }
        else
            $cobro_id = mysql_insert_id ();
     
        //-----------------------------------------------------------------------------
        $cantTipoPago     =  $_GET["cantTipoPago"]; 
        
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
                                        if(!$result){ echo 'fallo5';
                                            $error=1;}
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
                            $sql =  "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante) VALUES ($tipoPago,$cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit')";                     // Sin file
                         else
                             $sql =  "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,ban_id,sucursal,importe,fecha_emision,fecha_vto,firmante,cuit_firmante,files_id) VALUES ($tipoPago,$cobro_id,'$nro',$banco,'$sucursal',$importe,'$fechaEmision','$fechaVencimiento','$firmante','$cuit',$idFile)";   // Con file
                         break;
          
          /* Transferencia bancaria*/
          case 3:       if ($idFile == -1)
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,cuentabanco_id,importe,fecha_transferencia) VALUES ($tipoPago,$cobro_id,'$nro',$cuenta,$importe,'$fechaTransferencia')"; // Sin file           
                        else
                            $sql = "INSERT INTO cobros_detalle_pago (tipo_pago_id,cobros_id,nro,cuentabanco_id,importe,files_id,fecha_transferencia) VALUES ($tipoPago,$cobro_id,'$nro',$cuenta,$importe,$idFile,'$fechaTransferencia')"; // Sin file
                            break;
                        }
                        
                       $result = mysql_query($sql);               
                        
                       if (!$result){ echo 'fallo6'; echo "<br>QUERY : ".$sql;
                           $error = 1;}
             
                       
        }                
                       
                        
    /* RETENCIONES */


    if (isset($_POST["chkGanancias"])){
        $fecha      = gfecha($_POST["txtFecha1"]);
        $prefijo    = $_POST["txtPrefijo1"];
        $importe    = $_POST["txtImporte1"];
        $nro        = $_POST["txtNro1"];

       $sql = "INSERT INTO cobros_detalle_retencion (cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($cobro_id,1,'$fecha','$prefijo','$nro',$importe)"; 
       $result = mysql_query($sql);
        
        if (!$result){echo 'fallo7';
            $error = 1;  }
    }
    
    
    if (isset($_POST["chkIva"])){
        $fecha      = gfecha($_POST["txtFecha2"]);
        $prefijo    = $_POST["txtPrefijo2"];
        $importe    = $_POST["txtImporte2"];
        $nro        = $_POST["txtNro2"];
        $idiva      = $_POST["comboIva"];

        $sql = "INSERT INTO cobros_detalle_retencion (idiva,cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($idiva,$cobro_id,2,'$fecha','$prefijo','$nro',$importe)"; 
        $result = mysql_query($sql);
        
        if (!$result){ echo 'fallo8';
            $error = 1;}
    
        
    }
    
    
    if (isset($_POST["chkSUSS"])){
        $fecha      = gfecha($_POST["txtFecha3"]);
        $prefijo    = $_POST["txtPrefijo3"];
        $importe    = $_POST["txtImporte3"];
        $nro        = $_POST["txtNro3"];

        $sql = "INSERT INTO cobros_detalle_retencion (cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($cobro_id,4,'$fecha','$prefijo','$nro',$importe)"; 
        $result = mysql_query($sql);
        
        if (!$result){echo 'fallo9';
            $error = 1;    } 
    }
  $numeroRetencion=4;      
  for ($i = 1; $i <= $cantIIBB; $i++) {
    if (isset($_POST["chkIIBB$i"])){  // en la BD ret_id = 3 es IIBB
        $fecha      = gfecha($_POST["txtFecha$numeroRetencion"]);
        $prefijo    = $_POST["txtPrefijo$numeroRetencion"];
        $importe    = $_POST["txtImporte$numeroRetencion"];
        $nro        = $_POST["txtNro$numeroRetencion"];
        $provincia  = $_POST["comboProvincias$numeroRetencion"];
        
        $sql = "INSERT INTO cobros_detalle_retencion (provincias_id,cobros_id,ret_id,ret_fecha,ret_prefijo,ret_codigo,ret_importe) VALUES ($provincia,$cobro_id,3,'$fecha','$prefijo','$nro',$importe)";
        $result = mysql_query($sql);
        
        if (!$result){ echo "fallo10: <br>QUERY : ".$sql;
            $error = 1;    }        

        
     }
     $numeroRetencion++;
  }
     
     if ($error){
         mysql_query("ROLLBACK");
         echo "<br> Error en transacción";
         } else {
     mysql_query("COMMIT"); echo "Pago Registrado!";
     header("location:ver-alta-pago-grupo.php?grupo_fav=$id_grupo_fav");
                }
                
                
?>
