<?php
        include("validar.php");
        include("funciones.php");
        
        
        $fav_id = $_GET["fav_id"];
        include("conexion.php");
        
        /* ACTUALIZAMOS LA FECHA DE PAGO EN LA FACTURA*/
        $sql = "UPDATE factura_venta SET fav_fecha_pago = NOW() where fav_id = $fav_id";
        mysql_query($sql);
        
        /* ACTUALIZAMOS LAS ORDENES A ESTADO FINALIZADO PAGADO*/
        $sql = "UPDATE ordenes set est_id = 14 where gru_id = (SELECT gru_id from factura_venta where fav_id = $fav_id);";
        mysql_query($sql);
        
        header("location:lista-facturas.php");

?>
