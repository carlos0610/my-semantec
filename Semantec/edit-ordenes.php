<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_POST["ord_id"];   
        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]); 
        $rub_id = $_POST["rub_id"];
        $prv_id = $_POST["prv_idEdit"];  
        $est_id = $_POST["est_idEdit"];  
        
        $fecha = gfecha($_POST["fecha"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"]; 
        $ord_checkAbono = $_POST["ord_checkAbono"];
        $ord_abono_fecha = $_POST["ord_abono_fecha"];
        $es_abono = $_POST["es_abono"];
        // datos de filtro de listado
                  $elementoBusqueda=$_POST['filtrartxt']; 
                  $proveedorFiltro=$_POST['prv_id']; 
                  $estado_id=$_POST['est_id'];
                  $cli_id = $_POST['cli_idSucur']; 
                  $cli_idMaestro = $_POST['cli_id'];  
                  $unOrden=$_POST['orden'];
                  $contador=$_POST['contador'];
                  
                  $suc_id = $_POST['suc_id'];
                  
                  $pagina = $_GET["pagina"];                 
                  $action = $_GET["action"];
        
                  if($action==0){$action='listadoOrdenes';}
                  if($action==1){$action='listadoOrdenesMovimientos';}
               
        // fin 
        include("conexion.php");
        include("Modelo/modeloHistorialAbonos.php");
        include("Modelo/modeloOrdenes.php");
        include("Modelo/modeloAbonosDetalle.php");
       $error = 0; //variable para detectar error
        mysql_query("BEGIN"); // Inicio de Transacción
      //  cli_id = $cli_id,
        $sql = "UPDATE ordenes SET 
					ord_codigo = '$ord_codigo',
        				ord_descripcion = '$ord_descripcion',
                                        cli_id=$cli_id,
        				prv_id = $prv_id,
        				est_id = $est_id,
                                        rub_id = $rub_id,
                                      
                                        ";
                                        
                                        if ($est_id == 2){ 
                                        //$fecha = gfecha($fecha);    
                                        $sql .=" ord_plazo_proveedor = '$fecha', ";
        				
                                        }
                                        if ($est_id == 9){
                                        //$fecha = gfecha($fecha);
                                        $sql .=" ord_plazo = '$fecha', ";
                                        }                                       
                                        if ($est_id == 3){//estadoAprobadoBajoCosto
                                        $sql .=" fecha_aprobado_bajocosto = '$fecha', ";
                                        }
                                        if ($est_id == 11){ //estadoFinalizadoPendienteFacturacion
                                        $sql .=" fecha_pendiente_facturacion = '$fecha', ";
                                        }
                                        $sql .= " ord_costo = $ord_costo,
                                        ord_venta = $ord_venta
                                        WHERE ord_id = $ord_id;";
        
                                        
	mysql_query($sql);//modificacion de la orden
                // GUARDA HISTORIAL DE ABONO SI CUMPLE 
        echo $sql;
        echo 'ACA: ',$cli_id;
        if($es_abono!=1){ // si no tenia abono cargado en Si
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
        
        }
        $_SESSION["ord_id"] = $ord_id;
        // el de ALTAS
     
                /* RECUPERAMOS DATOS DEL FORMULARIO*/  
        $ord_descripcion = utf8_decode($_POST["ord_descripcionDetalle"]); 
        $ord_det_monto=$_POST["ord_det_monto"];
        $est_idDetalle = $_POST["est_idDetalle"];
        $estado_id_filtro = $_POST["est_id"];
        $prov_filtro = $_POST["prv_idEdit"];
        $checkCambiarEstado = $_POST["checkCambiarEstado"];
        // fecha de algunos estados los formatea
        $fecha="";
        $fecha .=  gfechaBD($_POST["fecha_detalle"]);
        if($fecha == "'--'")
             $fecha=" NOW() ";
        $usu_nombre = $_SESSION["usu_nombre"];
        $idFile = -1;
        $checkPortada= $_POST["checkPortada"];
        if($checkPortada=='')
            {$checkPortada=0;}   
        $checkPortadaDescripcion= $_POST["checkPortadaDescripcion"];
        if($checkPortadaDescripcion=='')
            {$checkPortadaDescripcion=0;}  
             
       if($checkCambiarEstado=='1'){    // si el check de agregar detalle orden esta activo    
        
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
        $sql_file = "INSERT INTO files (file_name, file_size, file_type, file_content,tabla , publico ) ".
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','detalles_ordenes',$checkPortada)";

        $result=mysql_query($sql_file);
                if(!$result)
                  {$error=1; echo $sql_file;}
        

        echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        } /*FIN DE ADJUNTAR ARCHIVO PARA DETALLE*/
        
                                                        
                                                        
                                                        
                                                        
        // buscar nombre del estado
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_idDetalle ";
        $resultado3 = mysql_query($sql);
        if(!$resultado3)
                     $error=1;
        $fila3 = mysql_fetch_array($resultado3);
        $est_nombre= $fila3["est_nombre"];
        //echo $est_nombre;
       
        $sql =  "UPDATE ordenes SET 
        						est_id = $est_idDetalle
                                                        WHERE ord_id = $ord_id";				  
	mysql_query($sql);//alta de la orden
       // $ord_id = mysql_insert_id();
        
        
        //NO ADJUNTO UN ARCHIVO
        if ($idFile == -1)  {
        $sql2 =  "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado, publico) VALUES 
                                                        ('$ord_descripcion',
                                                        $ord_det_monto,
                                                        $ord_id,
                                                        $fecha,
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre',
                                                        $checkPortadaDescripcion
                                                        )";
        }
        //SI ADJUNTO UN ARCHIVO
        else  
            {
        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        $idFile,
                                                        '$ord_descripcion',
                                                        $ord_det_monto,
                                                        $ord_id,
                                                        $fecha,
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre',
                                                        $checkPortadaDescripcion
                                                )";
        
                                                }
       // echo $sql2;
        $result=mysql_query($sql2); echo $sql2;
        if(!$result)
                     $error=1;
        
        

	$_SESSION["ord_id"] = $ord_id;
        

        switch ($est_idDetalle) {
    case 2://estadoEnviadoProveedor
    {$sql = "UPDATE ordenes SET ord_plazo_proveedor = $fecha where ord_id = $ord_id";$result=mysql_query($sql);echo $sql; break;}
    case 9://estadoConfirmarProveedor
    { $sql = "UPDATE ordenes SET ord_plazo = $fecha where ord_id = $ord_id"; $result=mysql_query($sql);echo $sql; break;}
    case 11://estadoFinalizadoPendienteFacturacion
    {$sql = "UPDATE ordenes SET fecha_pendiente_facturacion = $fecha where ord_id = $ord_id"; $result=mysql_query($sql); echo $sql;break;}
    case 3://estadoAprobadoBajoCosto
    { $sql = "UPDATE ordenes SET fecha_aprobado_bajocosto = $fecha where ord_id = $ord_id"; $result=mysql_query($sql);echo $sql;break;}
}
        
        

         if(!$result)
                     $error=1;
        
       }        
        
        
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
                  //        header("location:ver-alta-ordenes.php?action=2&est_id=$estado_id_filtro&prv_id=$prov_filtro"); 
                        	header("location:ver-alta-ordenes.php?action=2&filtrartxt=$elementoBusqueda&prv_id=$proveedorFiltro&est_id=$estado_id&suc_id=$suc_id&cli_id=$cli_idMaestro&orden=$unOrden&contador=$contador&pagina=$pagina&origen=$action");
                        }
        

        
        
        /* PEDAZO DEL ORDENES EDIT
	mysql_close();
        // paso los parametros  de filtro por url
	header("location:ver-alta-ordenes.php?action=2&filtrartxt=$elementoBusqueda&prv_id=$proveedorFiltro&est_id=$estado_id&suc_id=$suc_id&cli_id=$cli_idMaestro&orden=$unOrden&contador=$contador&pagina=$pagina&origen=$action
                "); */

?>
