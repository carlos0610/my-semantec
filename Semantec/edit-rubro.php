<?php
        include("validar.php");

        $rub_nombre = utf8_decode($_POST["rub_nombre"]);
        $id= $_POST["id"];


        
        
        
        
        
        include("conexion.php");      

             
        $sql = "UPDATE rubros SET
					rub_nombre = '$rub_nombre'

        		WHERE rub_id = $id";
                 echo $sql;     
		mysql_query($sql);
		$_SESSION["id"] = $id;
		mysql_close();
		header("location:form-edit-rubro.php?id=$id&actualizo=1");

?>