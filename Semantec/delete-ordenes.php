<?php
        include("validar.php");
        include("funciones.php");

        $ord_id = $_GET["orden_id"];

        include("conexion.php");
        include("Modelo/modeloHistorialAbonos.php");
        
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        $sql = "UPDATE ordenes SET 
					estado = 0

        			WHERE ord_id = $ord_id";
        

        echo $sql;
	$result =mysql_query($sql);//modificacion de la orden
        if (!$result)
                $error = 1; 
        
        
       $result =deleteHistorialAbonosWithOrdenId($ord_id);                 
       if (!$result)
                $error = 1;  
	
       
       $_SESSION["ord_id"] = $ord_id;

	if ($error){
          mysql_query("ROLLBACK");
               echo "<br> Error en transacción";
         } else {
     mysql_query("COMMIT");
	header("location:lista-ordenes.php");
        
        }

?>