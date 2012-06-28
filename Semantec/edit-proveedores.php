<?php
        include("validar.php");
        $prv_id = $_POST["prv_id"];
        $prv_nombre = utf8_decode($_POST["prv_nombre"]);
        //$prv_cuit = $_POST["prv_cuit"];
        $cuitA = $_POST["cuit_parteA"];
        $cuitB = $_POST["cuit_parteB"];
        $cuitC = $_POST["cuit_parteC"];
        $prv_cuit = "$cuitA$cuitB$cuitC";
        
        
        
        $iva_id = $_POST["iva_id"];
        $rub_id = utf8_decode($_POST["rub_id"]);
        
        $ubicacion_id = $_POST["ubicacion_id"];
        $provincia_id =  $_POST["select1"];
        $partidos_id = $_POST["select2"];
        $localidad_id  = $_POST["select3"];
        
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
        
        
        /* Actualizamos su ubicación*/
        $sql = "UPDATE ubicacion SET localidades_id = $localidad_id, partidos_id = $partidos_id, provincias_id = $provincia_id WHERE id = $ubicacion_id";
        mysql_query($sql);
        //$mensaje = $sql;
        
        
        
        /* Actualizamos los datos del proveedor */
        
        $sql = "UPDATE proveedores SET
        				prv_nombre = '$prv_nombre',
        				prv_cuit = '$prv_cuit',
        				iva_id = $iva_id,
        				rub_id = '$rub_id',
        				ubicacion_id = $ubicacion_id,
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
                               
                $rbt_cuenta = $_POST["rbt_cuenta"];
                if ($rbt_cuenta == "1"){
                    
                    $cut_id = $_POST["cut_id"];
                    $cue_nrobancaria = $_POST["cue_nrobancaria"];
                    $cue_cbu         = $_POST["cue_cbu"];
                    $ban_id          = $_POST["ban_id"];
                    $sql = "UPDATE cuentabanco_prv set cut_id =$cut_id,cue_nrobancaria ='$cue_nrobancaria',cue_cbu = '$cue_cbu' , ban_id= $ban_id   where prv_id = $prv_id";
                    $_SESSION["tienecuenta"]=true;
                    mysql_query($sql);
                    
                    $actualizo = mysql_affected_rows();
                    
                        //Si no actualizo es porque es una cuenta nueva, entonces la insertamos
                    if ($actualizo == 0){
                        $sql = "INSERT INTO cuentabanco_prv (prv_id,cut_id,cue_nrobancaria,cue_cbu) VALUES ($prv_id,$cut_id,$cue_nrobancaria,$cue_cbu)";
                        mysql_query($sql);
                    }
                }

		mysql_close();
		header("location:ver-alta-proveedores.php?action=2");

?>