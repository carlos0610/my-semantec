<?php
        include("validar.php");
        include("funciones.php");

        $fco_id = $_GET["fco_id"];
        

        include("conexion.php");
        mysql_query("BEGIN"); // Inicio de Transacción
        
        
        /* Eliminación lógica de la Factura de Venta*/
        $sql = "UPDATE factura_compra SET 
					estado = 0

        			WHERE fco_id = $fco_id";

	$result = mysql_query($sql);
        
        if(!$result)
        $error=1;

       
                /* Validación de la transacción */
                    if($error) 
                      {
                                mysql_query("ROLLBACK");
                                echo "Error en la transacción";
                                mysql_close();
                                
                        } 
                        else 
                        {
                                mysql_query("COMMIT");
                                mysql_close();
                                header("location:lista-facturas-compra.php");
                                
                        }




?>
