<?php
        include("validar.php");
        include("funciones.php");
        
        /*SI NO ES ADMIN NO PUEDE ELIMINAR LA ORDEN*/
        if($_SESSION["rol_id"] !=1){
			header("location:index-admin.php");
                        
	}else{

        $ord_id = $_GET["orden_id"];

        include("conexion.php");
        include("Modelo/modeloHistorialAbonos.php");
        
        $fecha_eliminacion = getdate();
        
        $fecha = $fecha_eliminacion[mday]."/".$fecha_eliminacion[mon]."/".$fecha_eliminacion[year]." ".$fecha_eliminacion[hours].":".$fecha_eliminacion[minutes].":".$fecha_eliminacion[seconds];
      
        mysql_query("BEGIN"); // Inicio de Transacción
        $error      = 0;
        
        
        $sql = "SELECT ord_codigo FROM ordenes WHERE ord_id = $ord_id";
        $rs = mysql_query($sql);  
        
        if(!$rs)
            $error = 1;
        
        /*AL ELIMINARSE UNA ORDEN SE LE ADICIONA LA FECHA DE ELIMINACIÓN AL CÓDIGO DE ORDEN 
          DE MANERA QUE EL CÓDIGO UTILIZADO PARA LA ORDEN VUELVA A ESTAR DISPONIBLE
         YA QUE ES UN CAMPO UNIQUE
         */
        
        $codigo = mysql_fetch_array($rs);
        $ord_codigo = $codigo["ord_codigo"];
        $nuevo_codigo_eliminado = $ord_codigo." - ".$fecha;  // <----- Just magic !
        
        
        $sql = "UPDATE ordenes SET estado = 0 ,ord_codigo = '$nuevo_codigo_eliminado' WHERE ord_id = $ord_id";
        
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
        }
?>