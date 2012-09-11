<?php
        include("validar.php");
        include("conexion.php");
        
        $usu_id = $_GET["usu_id"];
        
        $sql = "UPDATE usuarios_portal SET estado = 0 WHERE id = $usu_id";
        mysql_query($sql);
        
        mysql_close();
        header("location:lista-usuarios-portal.php");
        
?>
