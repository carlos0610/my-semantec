<?php
        include("validar.php");
        $prv_id = $_POST["prv_id"];
        $prv_nombre = utf8_decode($_POST["prv_nombre"]);
        $prv_cuit = $_POST["prv_cuit"];
        $iva_id = $_POST["iva_id"];
        $rub_id = utf8_decode($_POST["rub_id"]);
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
        
        include("conexion.php");
        $sql = "UPDATE proveedores SET
        				prv_nombre = '$prv_nombre',
        				prv_cuit = '$prv_cuit',
        				iva_id = $iva_id,
        				rub_id = '$rub_id',
        				zon_id = $zon_id,
        				prv_direccion = '$prv_direccion',
        				prv_telefono = '$prv_telefono',
                                        prv_fax = '$prv_fax',
                                        prv_cel = '$prv_cel',
                                        prv_alternativo = '$prv_alternativo',
                                        prv_urgencia  = '$prv_urgencia',
                                        prv_web   = '$prv_web',
                                        prv_email = '$prv_email',    
        				prv_notas = '$prv_notas'
        		WHERE prv_id = $prv_id";
		mysql_query($sql);
		$_SESSION["prv_id"] = $prv_id;
                
                if ($_SESSION["tienecuenta"]==true){
                    
                    $cut_id = $_POST["cut_id"];
                    $cue_nrobancaria = $_POST["cue_nrobancaria"];
                    $cue_cbu = $_POST["cue_cbu"];
                    $sql = "UPDATE cuentabanco_prv set cut_id =$cut_id,cue_nrobancaria =$cue_nrobancaria,cue_cbu = $cue_cbu where prv_id = $prv_id";
                    mysql_query($sql);
                    
                    
                }

		mysql_close();
		header("location:ver-alta-proveedores.php?action=2");

?>