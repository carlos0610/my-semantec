<?php
        include("validar.php");
        include("funciones.php");

        
        /* RECUPERAMOS DATOS DEL FORMULARIO*/
        $ord_id = $_POST["ord_id"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $ord_det_monto=$_POST["ord_det_monto"];
        $est_id = $_POST["est_id"];
        $fecha =  gfecha($_POST["fecha"]);
        $usu_nombre = $_SESSION["usu_nombre"];
        $idFile = -1;
        include("conexion.php");
        
        
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','detalles_ordenes')";

        mysql_query($sql_file);
        
        

        echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        } /*FIN DE ADJUNTAR ARCHIVO PARA DETALLE*/
        
                                                        
                                                        
                                                        
                                                        
        // buscar nombre del estado
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_id ";
        $resultado3 = mysql_query($sql);
        $fila3 = mysql_fetch_array($resultado3);
        $est_nombre= $fila3["est_nombre"];
        //echo $est_nombre;
       
        $sql =  "UPDATE ordenes SET 
        						est_id = $est_id
                                                        WHERE ord_id = $ord_id";
        echo $sql; 				  
	mysql_query($sql);//alta de la orden
       // $ord_id = mysql_insert_id();
        
        
        //NO ADJUNTO UN ARCHIVO
        if ($idFile == -1)  {
        $sql2 =  "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado) VALUES 
                                                        ('$ord_descripcion',
                                                        $ord_det_monto,
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
                                                        $ord_det_monto,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre'
                                                )";
        
                                                }
       // echo $sql2;
        mysql_query($sql2);
        
        
        

	$_SESSION["ord_id"] = $ord_id;
        
        /* UPDATEAR PLAZO DE PROVEEDOR */
        
        if ($est_id == 2){
        $sql = "UPDATE ordenes SET ord_plazo_proveedor = '$fecha' where ord_id = $ord_id";
        }else{
        $sql = "UPDATE ordenes SET ord_plazo = '$fecha' where ord_id = $ord_id";   
        }
        mysql_query($sql);
        mysql_close();

	header("location:ver-alta-ordenes.php?action=2");

?>