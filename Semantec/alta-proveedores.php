<?php
        include("validar.php");
        $prv_nombre = $_POST["prv_nombre"];
        $prv_cuit = $_POST["prv_cuit"];
        $iva_id = $_POST["iva_id"];
        $prv_rubro = utf8_decode($_POST["rub_id"]);
        $zon_id = $_POST["zon_id"];
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

        include("conexion.php");
        $sql = "INSERT INTO proveedores (prv_nombre,prv_cuit,rub_id,iva_id,zon_id,prv_direccion,prv_telefono,prv_fax,prv_cel,prv_alternativo,prv_urgencia,prv_web,prv_email,prv_notas,estado) VALUES (     										
        										'$prv_nombre',
        										'$prv_cuit',
        										$prv_rubro,
                                                                                        $iva_id,    
        										$zon_id,
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
		//$_SESSION["query"] = $sql;
                mysql_query($sql);
                $prv_id = mysql_insert_id();
		$_SESSION["prv_id"] = $prv_id;
                $_SESSION["query"] = $sql;
                
                if ($rbt_cuenta == "1"){
                    $cue_nrobancaria = $_POST["cue_nrobancaria"];
                    $cut_id          = $_POST["cut_id"];
                    $cue_cbu         = $_POST["cue_cbu"];
                    $sql = "INSERT INTO cuentabanco_prv (prv_id,cut_id,cue_nrobancaria,cue_cbu) VALUES ($prv_id,$cut_id,$cue_nrobancaria,$cue_cbu)";
                    $_SESSION["tienecuenta"] = true;
                    mysql_query($sql);             
                } else { $_SESSION["tienecuenta"] = false;}
                

		mysql_close();
		header("location:ver-alta-proveedores.php?action=1");

?>