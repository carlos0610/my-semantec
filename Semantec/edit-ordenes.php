<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_POST["ord_id"];
        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $cli_id = $_POST["cli_id"];
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        $ord_alta = date("Y-m-d");    // << -------
        $fecha = gfecha($_POST["fecha"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];


        

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
	header("location:ver-alta-ordenes.php?action=2");

?>