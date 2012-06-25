<?php
        include("validar.php");

        $ban_nombre = utf8_decode($_POST["ban_nombre"]);
        $ban_direccion = utf8_decode($_POST["ban_direccion"]);
        $ban_telefono = $_POST["ban_telefono"];
        $id= $_POST["id"];


        
        
        
        
        
        include("conexion.php");      

             
        $sql = "UPDATE banco SET
					ban_nombre = '$ban_nombre',
        				ban_direccion = '$ban_direccion',
        				ban_telefono = '$ban_telefono'

        		WHERE ban_id = $id";
                       
		mysql_query($sql);
		$_SESSION["id"] = $id;
		mysql_close();
		header("location:form-edit-banco.php?id=$id&actualizo=1");

?>