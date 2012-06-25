<?php
        include("validar.php");
        include("conexion.php");
        
        $usu_id = $_GET["usu_id"];
        
        $sql = "UPDATE usuarios SET estado = 0 WHERE usu_id = $usu_id";
        mysql_query($sql);
        
        mysql_close();
        header("location:lista-usuarios.php");
        
?>
