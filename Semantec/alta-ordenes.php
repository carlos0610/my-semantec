<?php
        include("validar.php");
        include("funciones.php");

        $ord_codigo = $_POST["ord_codigo"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $cli_id = $_POST["cli_id"];
        $prv_id = $_POST["prv_id"];
        $est_id = $_POST["est_id"];
        //$ord_alta = date("Y-m-d");
        $ord_plazo = gfecha($_POST["ord_plazo"]); 
        $ord_costo = $_POST["ord_costo"];
        $ord_venta = $_POST["ord_venta"];
        $usu_nombre = $_SESSION["usu_nombre"]; 
        $est_nombre = $_POST["est_nombre"];
        
        
        include("conexion.php");
        $idFile = -1;
        
        /*ADJUNTAR ARCHIVO PARA DETALLE*/
                    if($_FILES['userfile']['size']>0){      //SI ELIGIO UN ARCHIVO
        
                                $fileName = $_FILES['userfile']['name'];
                                $tmpName  = $_FILES['userfile']['tmp_name'];
                                $fileSize = $_FILES['userfile']['size'];
                                $fileType = $_FILES['userfile']['type'];
                                //$error    = $_FILES['userfile']['error'];

                                $fp      = fopen($tmpName, 'r');
                                $content = fread($fp, filesize($tmpName));
                                $content = addslashes($content);
                                fclose($fp);

                    if(!get_magic_quotes_gpc())
                        {
                        $fileName = addslashes($fileName);
                        }
        $sql_file = "INSERT INTO files (file_name, file_size, file_type, file_content,tabla ) ".
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','ordenes')";

        mysql_query($sql_file);
        
        

        echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        } /*FIN DE ADJUNTAR ARCHIVO PARA DETALLE*/
                                                        
        /*INSERTAMOS ORDEN*/
        
        $sql = "INSERT INTO ordenes (ord_codigo,ord_descripcion,cli_id,prv_id,est_id,ord_alta,ord_plazo,ord_costo,ord_venta,estado) VALUES (
        							
								'$ord_codigo',
        							'$ord_descripcion',
        						         $cli_id,
        							 $prv_id,
        							 $est_id,
        							 NOW(),
        							'$ord_plazo',
        							 $ord_costo,
                                                                 $ord_venta,
                                                                 1
        				    )";
	mysql_query($sql);//alta de la orden
        
        //echo $sql;
        
        $ord_id = mysql_insert_id();
        

        /* INSERTAMOS DETALLE DE ORDEN CON FOTO O SIN FOTO*/
        
        if ($idFile == -1)  {
        $sql2 =  "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado) VALUES 
                                                        ('$ord_descripcion',
                                                        0,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre')";
        }
        //SI ADJUNTO UN ARCHIVO
        else  
            {
        $sql2 = "INSERT INTO ordenes_detalle VALUES (
                                                        NULL,
                                                        $idFile,
                                                        '$ord_descripcion',
                                                        0,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre'
                                                )";      
                                                }
 
        mysql_query($sql2);
        echo $sql2;
	$_SESSION["ord_id"] = $ord_id;
        $_SESSION["query"] = $sql2;
        mysql_close();
	header("location:ver-alta-ordenes.php?action=1");

?>