<?php
        include("validar.php");
        include("conexion.php");
        
        $cli_id = $_GET["cli_id"];
        
        /*INICIAR TRANSACCIÓN*/
        
        // BAJA LÓGICA Cliente
        $sql = "UPDATE clientes SET 
				estado = 0
        			WHERE cli_id = $cli_id";
	mysql_query($sql);
        
        //BAJA LÓGICA de ORDENES del Cliente
        $sql = "UPDATE ordenes SET 
				estado = 0
        			WHERE cli_id = $cli_id";
	mysql_query($sql);
        
        //BAJA LÓGICA de Cuenta Corriente de Cliente
        $sql = "UPDATE cuentacorriente_cliente SET estado = 0 where cli_id =$cli_id"; 
        mysql_query($sql);
        
        
        /*FINALIZAR TRANSACCIÓN*/
        
        //echo $sql;
	$_SESSION["cli_id"] = $cli_id;
	mysql_close();
	header("location:lista-clientes.php");

?>
