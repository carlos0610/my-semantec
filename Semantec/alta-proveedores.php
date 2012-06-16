<?php
        include("validar.php");
        include("conexion.php");
        $prv_nombre = $_POST["prv_nombre"];
        
        $cuitA = $_POST["cuit_parteA"];
        $cuitB = $_POST["cuit_parteB"];
        $cuitC = $_POST["cuit_parteC"];
        $prv_cuit = "$cuitA$cuitB$cuitC";
        $iva_id = $_POST["iva_id"];
        $prv_rubro = utf8_decode($_POST["rub_id"]);
        
        
        
        /* OBTENEMOS LOS DATOS DE UBICACIÓN DE LOS SELECTS*/
        $provincia_id   = $_POST["select1"];
        $partido_id     = $_POST["select2"];
        $localidad_id   = $_POST["select3"];
        
        /* GENERAMOS UN CÓDIGO DE UBICACIÓN EN LA TABLA UBICACIÓN */
        
        $sql = "INSERT INTO ubicacion (provincias_id,partidos_id,localidades_id) VALUES ($provincia_id,$partido_id,$localidad_id)";
        mysql_query($sql);
        
       $ubicacion_id = mysql_insert_id();
        
        
        
        $prv_direccion = utf8_decode($_POST["prv_direccion"]);
        $prv_telefono = $_POST["prv_telefono"];
        $prv_fax = $_POST["prv_fax"];
        $prv_cel = $_POST["prv_cel"];
        $prv_alternativo = $_POST["prv_alternativo"];
        $prv_urgencia = $_POST["prv_urgencia"];
        $prv_web = $_POST["prv_web"];
        $prv_email = $_POST["prv_email"];
        $prv_notas = utf8_decode($_POST["prv_notas"]);
        $rbt_cuenta = $_POST["rbt_cuenta"];

        
        $sql = "INSERT INTO proveedores (prv_nombre,prv_cuit,rub_id,iva_id,ubicacion_id,prv_direccion,prv_telefono,prv_fax,prv_cel,prv_alternativo,prv_urgencia,prv_web,prv_email,prv_notas,estado) VALUES (     										
        										'$prv_nombre',
        										'$prv_cuit',
        										$prv_rubro,
                                                                                        $iva_id,    
        										$ubicacion_id,
        										'$prv_direccion',
        										'$prv_telefono',
                                                                                        '$prv_fax',
                                                                                        '$prv_cel',
                                                                                        '$prv_alternativo',
                                                                                        '$prv_urgencia',
                                                                                        '$prv_web',
                                                                                        '$prv_email',
        										'$prv_notas',
                                                                                        1
        										)";
		
                mysql_query($sql);
                $prv_id = mysql_insert_id(); // <-- Proveedor recién ingresado
                
		$_SESSION["prv_id"] = $prv_id;
                $_SESSION["query"] = $sql;
                
                if ($rbt_cuenta == "1"){
                    $cue_nrobancaria = $_POST["cue_nrobancaria"];
                    $cut_id          = $_POST["cut_id"];
                    $cue_cbu         = $_POST["cue_cbu"];
                    $ban_id          = $_POST["ban_id"];
                    $sql = "INSERT INTO cuentabanco_prv (ban_id,prv_id,cut_id,cue_nrobancaria,cue_cbu) VALUES ($ban_id,$prv_id,$cut_id,$cue_nrobancaria,$cue_cbu)";
                    $_SESSION["tienecuenta"] = true;
                    mysql_query($sql);   
                    echo $sql;
                } else { $_SESSION["tienecuenta"] = false;}
                
                
                
                /* REGISTRO CUENTA CORRIENTE DE PROVEEDOR */
                $sql = "INSERT INTO cuentacorriente_prv (prv_id) VALUES ($prv_id)";
                mysql_query($sql);

		mysql_close();
                
                
                //echo "QUERY: ".$mensaje2;
                header("location:ver-alta-proveedores.php?action=1");

?>