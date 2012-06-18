<?php
        include("validar.php");
        $cli_id = $_POST["cli_id"];
        $cli_nombre = utf8_decode($_POST["cli_nombre"]);
        //$cli_cuit = $_POST["cli_cuit"];
        $cuitA = $_POST["cuit_parteA"];
        $cuitB = $_POST["cuit_parteB"];
        $cuitC = $_POST["cuit_parteC"];
        $cli_cuit = "$cuitA$cuitB$cuitC";
        
        
        $iva_id = $_POST["iva_id"];
        
        
        $cli_direccion = utf8_decode($_POST["cli_direccion"]);
        $cli_direccion_fiscal = utf8_decode($_POST["cli_direccion_fiscal"]);
        $cli_telefono = $_POST["cli_telefono"];
        $cli_notas = utf8_decode($_POST["cli_notas"]);

        $ubicacion_id = $_POST["ubicacion_id"];
        $provincia_id =  $_POST["select1"];
        $partidos_id = $_POST["select2"];
        $localidad_id  = $_POST["select3"];
        
        
        
        
        
        
        include("conexion.php");
        
        $sql = "UPDATE ubicacion SET localidades_id = $localidad_id, partidos_id = $partidos_id, provincias_id = $provincia_id WHERE id = $ubicacion_id";
        mysql_query($sql);
             
        $sql = "UPDATE clientes SET
					cli_nombre = '$cli_nombre',
        				cli_cuit = '$cli_cuit',
        				iva_id = $iva_id,
        				ubicacion_id = $ubicacion_id,
        				cli_direccion = '$cli_direccion',
                                        cli_direccion_fiscal = '$cli_direccion_fiscal',    
        				cli_telefono = '$cli_telefono',
        				cli_notas = '$cli_notas'
        		WHERE cli_id = $cli_id";

		mysql_query($sql);
		$_SESSION["cli_id"] = $cli_id;

		mysql_close();
		header("location:ver-alta-clientes.php?action=2");

?>