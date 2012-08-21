<?php
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloAbonosDetalle.php");
        include("Modelo/modeloHistorialAbonos.php");
        include("Modelo/modeloOrdenes.php");
        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $cli_id = $_POST["suc_id"]; 
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        //$ord_alta = date("Y-m-d");
        $ord_alta = $_POST["ord_alta"];
        $ord_alta = gfecha($ord_alta);
        //$ord_plazo = gfecha($_POST["ord_plazo"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];
        $usu_nombre = $_SESSION["usu_nombre"];        
        $est_nombre = $_POST["est_nombre"];
        $usu_id     = $_SESSION["usu_id"];
        $ord_checkAbono = $_POST["ord_checkAbono"];
        $ord_abono_fecha = $_POST["ord_abono_fecha"];
        

  //      echo $ord_plazo;
        
        
        $idFile = -1;
                $error = 0; //variable para detectar error
                mysql_query("BEGIN"); // Inicio de Transacción
        /*ADJUNTAR ARCHIVO PARA DETALLE*/
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','ordenes')";

        $result=mysql_query($sql_file);
                        if(!$result)
                     $error=1;
        

  //      echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        } /*FIN DE ADJUNTAR ARCHIVO PARA DETALLE*/
                                                        
        /*INSERTAMOS ORDEN*/
        $sql = "INSERT INTO ordenes (usu_id,ord_codigo,ord_descripcion,cli_id,prv_id,est_id,ord_alta,ord_costo,ord_venta,estado) VALUES (
        							
                                                                 $usu_id,
								 '$ord_codigo',
        							'$ord_descripcion',
        						         $cli_id,
        							 $prv_id,
        							 $est_id,
        							 '$ord_alta',
        							 $ord_costo,
                                                                 $ord_venta,
                                                                 1
                                                                 
        				    )";
	$result=mysql_query($sql);//alta de la orden
                     if(!$result){
                     $error=1; echo 'PAPA';}
        $mensaje = $sql;
        
        //echo "QUERY".$sql;
        
        $ord_id = mysql_insert_id();
        // GUARDA HISTORIAL DE ABONO SI CUMPLE 
        if($ord_checkAbono=='')
            {$ord_checkAbono=0;}
        else{ //si esta checking
            if(cantAbonoDetalle_AbonoIdWithCliId($cli_id)>0) //si hay abonos registrados para esa sucursal
                {
                      $abonoDetalleId=getAbonoDetalle_AbonoIdWithCliId($cli_id); // obtengo id del abono
                      $ord_abono_fecha=fechaConDia1($ord_abono_fecha); 
                      if(cantHistorialAbonosWithAbonoIdAndFecha($abonoDetalleId,$ord_abono_fecha)==0)// si no hay abonos registrados para la fecha ingresada se guarda
                      {
                          $result=saveHistorialAbonos($abonoDetalleId,$ord_id,$ord_abono_fecha);
                          if(!$result)
                             $error=1;
                      }
                      else
                      {
                          $ord_checkAbono=3;
                      }
                      
                      
                 }
             else
             {
                 $ord_checkAbono=2;
             }
                  
          }
          //Abono $ord_checkAbono 0 si
          //                      1 no
          //                      2 sucursal no registrada con Abono
          //                      3 Abono del mes ya utilizado
          $result=updateOrdenesEsAbono($ord_id,$ord_checkAbono);
           if(!$result)
               $error=1;
        /* INSERTAMOS DETALLE DE ORDEN CON FOTO O SIN FOTO*/
        
        if ($idFile == -1)  {
        $sql2 =  "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado) VALUES 
                                                        ('$ord_descripcion',
                                                        0,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre')";
        }
        //SI ADJUNTO UN ARCHIVO
        else  
            {
        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        $idFile,
                                                        '$ord_descripcion',
                                                        0,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre'
                                                )";      
                                                }
 
                        $result=mysql_query($sql2);
                     if(!$result)
                     $error=1;
        
        
        /* INSERTAMOS ORDEN EN DETALLE DE CUENTA CORRIENTE */
        
        
        $sql = "SELECT ccc_id from cuentacorriente_cliente where cli_id = $cli_id";
        
        $cuenta_corriente = mysql_query($sql);
        $id_cuenta = mysql_fetch_row($cuenta_corriente);
         
        $sql = "INSERT INTO detalle_corriente_cliente (det_dcc_id,ccc_id) VALUES ($ord_id,$id_cuenta[0])";
        $result=mysql_query($sql);  // ACA LA TRANSACCION ME MARCA ERROR REVISAR

        
        //echo $sql2;
	$_SESSION["ord_id"] = $ord_id;
        $_SESSION["query"] = $sql2;
        
        
                              if($error) 
                      {
                            mysql_query("ROLLBACK");
                            echo "Error en la transaccion";
                             mysql_close();
                          //  header("location:ver-alta-clientes.php?action=4");
                        } 
                        else 
                        {
                        mysql_query("COMMIT");
                         mysql_close();
                        echo "Transacción exitosa";
                              header("location:ver-alta-ordenes.php?action=1&origenOtroForm=altaOrden");
                        }


?>