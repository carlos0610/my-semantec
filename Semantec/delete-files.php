<?php
        include("validar.php");
        include("funciones.php");

        $id_file    = $_GET["id"];
        $ord_id     = $_GET["ord_id"];

        include("conexion.php");
        mysql_query("BEGIN"); // Inicio de Transacción
        
        
        
        
        
        /* Obtenemos el ord_codigo de la orden para enviarlo como parametro a la siguiente vista */
        $sql            = "SELECT ord_codigo FROM ordenes WHERE ord_id = $ord_id";
        $result         = mysql_query($sql);
        $fila_codigo    = mysql_fetch_array($result);
        $ord_codigo     = $fila_codigo["ord_codigo"];
                
        
        if(!$result) /*Para alertar a la transaccion en caso de error */
        $error=1;
        
        /* Eliminación de la FK de files dentro del detalle de orden */

        $sql = "UPDATE ordenes_detalle set files_id = null WHERE files_id = $id_file";
        $result = mysql_query($sql);
        
        if(!$result)
        $error=1;
        
        /* Eliminación del archivo FILE */
        $sql = "DELETE FROM files  
					

        			WHERE id = $id_file";

	$result = mysql_query($sql);
        
        if(!$result)
        $error=1;
        
        
           
                /* Validación de la transacción */
                    if($error) 
                      {
                                mysql_query("ROLLBACK");
                                echo "Error en la transaccion . Vuelva a intentarlo";
                                mysql_close();
                                //  header("location:ver-alta-clientes.php?action=4");
                        } 
                        else 
                        {
                                mysql_query("COMMIT");
                                mysql_close();
                                header("location:lista-req-ordenes.php?orden=$ord_codigo&action=1&eliminado=ok");
                                
                        }
        
        
	

?>