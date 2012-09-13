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
                  $cli_id = $_POST['cli_idSucur']; echo ' Cliente sucursal: ',$cli_id;
                  $cli_idMaestro = $_POST['cli_id'];  
                  $unOrden=$_POST['orden'];
                  $contador=$_POST['contador'];

                  $pagina = $_GET["pagina"];
                  $action = $_GET["action"];
        
                  if($action==0){$action='listadoOrdenes';}
                  if($action==1){$action='listadoOrdenesMovimientos';}
               
        // fin 
        include("conexion.php");
        include("Modelo/modeloHistorialAbonos.php");
        include("Modelo/modeloOrdenes.php");
        include("Modelo/modeloAbonosDetalle.php");
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

	mysql_close();
        // paso los parametros  de filtro por url
	header("location:ver-alta-ordenes.php?action=2&filtrartxt=$elementoBusqueda&prv_id=$proveedorFiltro&est_id=$estado_id&suc_id=$cli_id&cli_id=$cli_idMaestro&orden=$unOrden&contador=$contador&pagina=$pagina&origen=$action
                ");

?>
