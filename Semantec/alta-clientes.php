<?php
        include("validar.php");
       
        $cli_nombre = utf8_decode($_POST["cli_nombre"]);
        $cli_cuitA = $_POST["cli_cuit_parteA"];
        $cli_cuitB = $_POST["cli_cuit_parteB"];
        $cli_cuitC = $_POST["cli_cuit_parteC"];
        $cli_cuit = "$cli_cuitA$cli_cuitB$cli_cuitC";
        
        
        
        $iva_id = $_POST["iva_id"];        
        $cli_direccion = utf8_decode($_POST["cli_direccion"]);
        $cli_direccion_fiscal = utf8_decode($_POST["cli_direccion_fiscal"]);
        $cli_telefono = $_POST["cli_telefono"];
        $cli_notas = utf8_decode($_POST["cli_notas"]);
        $cli_SucursalDetalle = utf8_decode($_POST["Sucursal"]);
        
       
        include("conexion.php");
        /* OBTENEMOS LOS DATOS DE UBICACIÓN DE LOS SELECTS*/
        $provincia_id   = $_POST["select1"];
        $partido_id     = $_POST["select2"];
        $localidad_id   = $_POST["select3"];
        
        /* GENERAMOS UN CÓDIGO DE UBICACIÓN EN LA TABLA UBICACIÓN */
        
        $sql = "INSERT INTO ubicacion (provincias_id,partidos_id,localidades_id) VALUES ($provincia_id,1,1)";
        mysql_query($sql);
        echo $sql;
        $ubicacion_id = mysql_insert_id();
        echo $ubicacion_id;
        
            if (isset($_REQUEST['chkSucursal']))
                $sucursal = $_POST["comboClientes"];
            else
                $sucursal = "NULL";
        
        
                $sql = "INSERT INTO clientes (sucursal_id,cli_nombre,cli_cuit,iva_id,cli_rubro,ubicacion_id,cli_direccion,cli_direccion_fiscal,cli_telefono,cli_notas,estado,sucursal) VALUES (
        										$sucursal,
        										'$cli_nombre',
        										'$cli_cuit',
        										$iva_id,
        										1,
        										$ubicacion_id,
        										'$cli_direccion',
                                                                                        '$cli_direccion_fiscal',       
        										'$cli_telefono',
        										'$cli_notas',
                                                                                        1,
                                                                                        '$cli_SucursalDetalle'
        										)";
                                                                                 
                mysql_query($sql);
                


                
                echo "QUERY DE INSERT CLIENTE : ".$sql;
                
                
                $idCliente = mysql_insert_id();
		$_SESSION["cli_id"] = $idCliente;
                
                /* CREAMOS UNA CUENTA CORRIENTE PARA EL CLIENTE INGRESADO SIEMPRE Y CUANDO NO SEA UNA SUCURSAL */
                if (!isset($_REQUEST['chkSucursal'])){
                    $sql = "INSERT INTO cuentacorriente_cliente  (cli_id,estado) VALUES ($idCliente,1)";
                    mysql_query($sql);
                    
                } else {
                    /* SINO BUSCAMOS EL ID DE LA CUENTA CORRIENTE DE LA SUCURSAL Y LA INSERTAMOS COMO SU CUENTA CORRIENTE */
                        $sql = "select ccc_id  from cuentacorriente_cliente where cli_id = $sucursal";
                        $resultado = mysql_query($sql);
                        $cuenta_corriente = mysql_fetch_array($resultado);
                        
                        $id_cuenta_corriente =  $cuenta_corriente['ccc_id'];
                        
                        $sql = "INSERT INTO cuentacorriente_cliente (ccc_id, cli_id, estado) VALUES ($id_cuenta_corriente, $idCliente, 1)";
                        mysql_query($sql);
                        
                        }
                        
                        mysql_close();
		
                        
                if ($sucursal != "NULL"){
                header("location:ver-alta-clientes.php?action=3");
                }else{
                header("location:ver-alta-clientes.php?action=1");                       
                }
                        
?>