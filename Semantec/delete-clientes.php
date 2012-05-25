<?php
        include("validar.php");
        include("funciones.php");       
        include("conexion.php");
        
        $cli_id = $_GET["cli_id"];
        // BAJA LOGICA Cliente
        $sql = "UPDATE clientes SET 
				estado = 0
        			WHERE cli_id = $cli_id";
	mysql_query($sql);
        
        //BAJA LOGICA de ORDENES del Cliente
        $sql = "UPDATE ordenes SET 
				estado = 0
        			WHERE cli_id = $cli_id";
	mysql_query($sql);
        
        
        echo $sql;
	$_SESSION["cli_id"] = $cli_id;
	mysql_close();
	header("location:lista-clientes.php");

?>
