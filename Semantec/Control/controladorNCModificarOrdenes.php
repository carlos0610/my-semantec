<?php
        include("validar.php");
        include("funciones.php");

        $fav_id = $_GET["fav_id"];
        $grup_id = $_GET["grup_id"];

        
        /*VALIDACIÓN : SI NO ES ADMIN NO PUEDE ELIMINAR LA FACTURA*/
         if($_SESSION["rol_id"] !=1){
			header("location:index-admin.php");
                        
	}else{
        
        
        include("conexion.php");
        mysql_query("BEGIN"); // Inicio de Transacción
        
        
        /* Eliminación lógica de la Factura de Venta*/
        $sql = "UPDATE factura_venta SET 
					estado = 0

        			WHERE fav_id = $fav_id";

	$result = mysql_query($sql);
        
        if(!$result)
        $error=1;
        
        /* Eliminación de los movimientos en la cuenta corriente de la factura eliminada */
        
        $sql = "UPDATE detalle_corriente_cliente SET 
                                        
                                        estado = 0 
        
                                WHERE fav_id = $fav_id";
        $result = mysql_query($sql);
        
        if(!$result)
        $error=1;
        /* volver al estado pendiente de facturacion a la ordenes afectadas*/
        echo $grup_id;
        $sql = "UPDATE ordenes SET                                        
                                        est_id = 11 ,
                                        gru_id= NULL
                                WHERE gru_id = $grup_id";
        $result = mysql_query($sql);
        
        if(!$result)
        $error=1;
        
        
                /* Validación de la transacción */
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
                                //echo "Transacción exitosa";
                                $_SESSION["fav_id"] = $fav_id;
                                header("location:lista-facturas.php");
                                
                        }
        
        
        }

?>