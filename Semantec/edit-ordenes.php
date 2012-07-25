<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_POST["ord_id"];   
        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]); 
       // $cli_id = $_POST["suc_id"];
        $prv_id = $_POST["prv_idEdit"];  
        $est_id = $_POST["est_idEdit"];  echo $est_id;
        $ord_alta = date("Y-m-d");    // << -------
        $fecha = gfecha($_POST["fecha"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];
        // datos de filtro de listado
                  $elementoBusqueda=$_POST['filtrartxt']; 
                  $proveedorFiltro=$_POST['prv_id']; 
                  $estado_id=$_POST['est_id'];
                  $cli_id = $_POST['suc_id'];
                  $cli_idMaestro = $_POST['cli_id'];  
                  $unOrden=$_POST['orden'];
                  $contador=$_POST['contador'];

                  $pagina = $_GET["pagina"];
                  $action = $_GET["action"];
        
                  if($action==0){$action='listadoOrdenes';}
                  if($action==1){$action='listadoOrdenesMovimientos';}
               
        // fin 
        include("conexion.php");
        
      //  cli_id = $cli_id,
        $sql = "UPDATE ordenes SET 
					ord_codigo = '$ord_codigo',
        				ord_descripcion = '$ord_descripcion',
        				
        				prv_id = $prv_id,
        				est_id = $est_id,
                                        ord_alta= '$ord_alta',";
                                        
                                        if ($est_id == 2){ 
                                        //$fecha = gfecha($fecha);    
                                        $sql .="ord_plazo_proveedor = '$fecha', ";
        				
                                        }
                                        if ($est_id == 9){
                                        //$fecha = gfecha($fecha);
                                        $sql .="ord_plazo = '$fecha', ";
                                        }
                                        
                                        $sql .= "ord_costo = $ord_costo,
                                        ord_venta = $ord_venta
                                        WHERE ord_id = $ord_id;";
        				

	mysql_query($sql);//modificacion de la orden
        $_SESSION["ord_id"] = $ord_id;

	mysql_close();
        // paso los parametros  de filtro por url
	header("location:ver-alta-ordenes.php?action=2&filtrartxt=$elementoBusqueda&prv_id=$proveedorFiltro&est_id=$estado_id&suc_id=$cli_id&cli_id=$cli_idMaestro&orden=$unOrden&contador=$contador&pagina=$pagina&origen=$action
                ");

?>