<?php
        include("validar.php");
        $cli_nombre = utf8_decode($_POST["cli_nombre"]);
        $cli_cuit = $_POST["cli_cuit"];
        $iva_id = $_POST["iva_id"];
        $cli_rubro = utf8_decode($_POST["rub_id"]);
        $zon_id = $_POST["zon_id"];
        $cli_direccion = utf8_decode($_POST["cli_direccion"]);
        $cli_telefono = $_POST["cli_telefono"];
        $cli_notas = utf8_decode($_POST["cli_notas"]);

        include("conexion.php");
        $sql = "INSERT INTO clientes (cli_nombre,cli_cuit,iva_id,cli_rubro,zon_id,cli_direccion,cli_telefono,cli_notas,estado)VALUES (
        										
        										'$cli_nombre',
        										'$cli_cuit',
        										$iva_id,
        										'$cli_rubro',
        										$zon_id,
        										'$cli_direccion',
        										$cli_telefono,
        										'$cli_notas',
                                                                                        1        
        										)";
		mysql_query($sql);
		$_SESSION["cli_id"] = mysql_insert_id();

		mysql_close();
		header("location:ver-alta-clientes.php?action=1");

?>