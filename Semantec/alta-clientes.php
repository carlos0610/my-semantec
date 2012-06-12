<?php
        include("validar.php");
        $cli_nombre = utf8_decode($_POST["cli_nombre"]);
        $cli_cuitA = $_POST["cli_cuit_parteA"];
        $cli_cuitB = $_POST["cli_cuit_parteB"];
        $cli_cuitC = $_POST["cli_cuit_parteC"];
        $cli_cuit = "$cli_cuitA$cli_cuitB$cli_cuitC";
        
        echo $cli_cuit;
        
        $iva_id = $_POST["iva_id"];        
        $cli_direccion = utf8_decode($_POST["cli_direccion"]);
        $cli_direccion_fiscal = utf8_decode($_POST["cli_direccion_fiscal"]);
        $cli_telefono = $_POST["cli_telefono"];
        $cli_notas = utf8_decode($_POST["cli_notas"]);
        
        
       
        include("conexion.php");
        /* OBTENEMOS LOS DATOS DE UBICACIÓN DE LOS SELECTS*/
        $provincia_id   = $_POST["select1"];
        $partido_id     = $_POST["select2"];
        $localidad_id   = $_POST["select3"];
        
        /* GENERAMOS UN CÓDIGO DE UBICACIÓN EN LA TABLA UBICACIÓN */
        
        $sql = "INSERT INTO ubicacion (provincias_id,partidos_id,localidades_id) VALUES ($provincia_id,$partido_id,$localidad_id)";
        mysql_query($sql);
        
       $ubicacion_id = mysql_insert_id();
        
        
        
        
        
        $sql = "INSERT INTO clientes (cli_nombre,cli_cuit,iva_id,cli_rubro,ubicacion_id,cli_direccion,cli_direccion_fiscal,cli_telefono,cli_notas,estado)VALUES (
        										
        										'$cli_nombre',
        										'$cli_cuit',
        										$iva_id,
        										1,
        										$ubicacion_id,
        										'$cli_direccion',
                                                                                        '$cli_direccion_fiscal',       
        										$cli_telefono,
        										'$cli_notas',
                                                                                        1        
        										)";
		mysql_query($sql);
                //$_SESSION["query"] = $sql;
                //echo "QUERY DE INSERT CLIENTE : ".$sql;
                echo $sql;
                
                $idCliente = mysql_insert_id();
		$_SESSION["cli_id"] = $idCliente;
                
                /* CREAMOS UNA CUENTA CORRIENTE PARA EL CLIENTE INGRESADO */
                
                $sql = "INSERT INTO cuentacorriente_cliente  (cli_id,estado) VALUES ($idCliente,1)";
                
                mysql_query($sql);
     

		mysql_close();
		header("location:ver-alta-clientes.php?action=1");

?>