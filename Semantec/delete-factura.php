<?php
        include("validar.php");
        include("funciones.php");

        $fav_id = $_GET["fav_id"];

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
        
        
	

?>