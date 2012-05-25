<?php
        include("validar.php");
        $cli_id = $_POST["cli_id"];
        $cli_nombre = utf8_decode($_POST["cli_nombre"]);
        $cli_cuit = $_POST["cli_cuit"];
        $iva_id = $_POST["iva_id"];
        //$cli_rubro = utf8_decode($_POST["rub_id"]);
        $zon_id = $_POST["zon_id"];
        $cli_direccion = utf8_decode($_POST["cli_direccion"]);
        $cli_telefono = $_POST["cli_telefono"];
        $cli_notas = utf8_decode($_POST["cli_notas"]);

        include("conexion.php");
        $sql = "UPDATE clientes SET
					cli_nombre = '$cli_nombre',
        				cli_cuit = '$cli_cuit',
        				iva_id = $iva_id,
        				zon_id = $zon_id,
        				cli_direccion = '$cli_direccion',
        				cli_telefono = $cli_telefono,
        				cli_notas = '$cli_notas'
        		WHERE cli_id = $cli_id";

		mysql_query($sql);
		$_SESSION["cli_id"] = $cli_id;

		mysql_close();
		header("location:ver-alta-clientes.php?action=2");

?>