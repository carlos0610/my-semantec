<?php
        include("validar.php");
        include("funciones.php");
        
        
        $fav_id = $_GET["fav_id"];
        $ccc_id = $_GET["ccc_id"];
        
        include("conexion.php");
        $usuario = $_SESSION["usu_nombre"];
        
        /* ACTUALIZAMOS LA FECHA DE PAGO EN LA FACTURA*/
        $sql = "UPDATE factura_venta SET fav_fecha_pago = NOW(), usu_nombre = '$usuario' where fav_id = $fav_id";
        mysql_query($sql);
        
  
        /* ACTUALIZAMOS LAS ORDENES A ESTADO FINALIZADO PAGADO*/
        $sql = "UPDATE ordenes set est_id = 14 where gru_id = (SELECT gru_id from factura_venta where fav_id = $fav_id);";
        mysql_query($sql);
    
        /* INSERTAMOS EL PAGO COMO UN DETALLE EN LA CUENTA CORRIENTE */
        $sql = "INSERT into detalle_corriente_cliente (ccc_id,fav_id,estado) VALUES ($ccc_id,$fav_id,1)";
        mysql_query($sql);
        
        
        header("location:lista-facturas.php");

?>
