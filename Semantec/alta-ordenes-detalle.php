<?php
        include("validar.php");
        include("funciones.php");

        
        /* RECUPERAMOS DATOS DEL FORMULARIO*/
        $ord_id = $_POST["ord_id"];
        $ord_descripcion = utf8_decode($_POST["ord_descripcion"]);
        $ord_det_monto=$_POST["ord_det_monto"];
        $est_id = $_POST["est_id"];
        $estado_id_filtro = $_GET["estado_id_filtro"];
        $prov_filtro = $_GET["prov_filtro"];
        $fecha =  gfecha($_POST["fecha"]);echo $fecha;
        $usu_nombre = $_SESSION["usu_nombre"];
        $idFile = -1;
        $checkPortada= $_POST["checkPortada"];
        if($checkPortada=='')
            {$checkPortada=0;}   
        $checkPortadaDescripcion= $_POST["checkPortadaDescripcion"];
        if($checkPortadaDescripcion=='')
            {$checkPortadaDescripcion=0;}  
        include("conexion.php");
                $error = 0; //variable para detectar error
                mysql_query("BEGIN"); // Inicio de Transacción
        
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
        $sql_file = "INSERT INTO files (file_name, file_size, file_type, file_content,tabla , publico ) ".
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','detalles_ordenes',$checkPortada)";

        $result=mysql_query($sql_file);
                if(!$result)
                  {$error=1; echo $sql_file;}
        

        echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        } /*FIN DE ADJUNTAR ARCHIVO PARA DETALLE*/
        
                                                        
                                                        
                                                        
                                                        
        // buscar nombre del estado
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_id ";
        $resultado3 = mysql_query($sql);
        if(!$resultado3)
                     $error=1;
        $fila3 = mysql_fetch_array($resultado3);
        $est_nombre= $fila3["est_nombre"];
        //echo $est_nombre;
       
        $sql =  "UPDATE ordenes SET 
        						est_id = $est_id
                                                        WHERE ord_id = $ord_id";				  
	mysql_query($sql);//alta de la orden
       // $ord_id = mysql_insert_id();
        
        
        //NO ADJUNTO UN ARCHIVO
        if ($idFile == -1)  {
        $sql2 =  "INSERT INTO ordenes_detalle (ord_det_descripcion,ord_det_monto,ord_id,ord_det_fecha,usu_nombre,estado,nombre_estado, publico) VALUES 
                                                        ('$ord_descripcion',
                                                        $ord_det_monto,
                                                        $ord_id,
                                                        NOW(),
                                                        '$usu_nombre',
                                                        1,
                                                        '$est_nombre',
                                                        $checkPortadaDescripcion
                                                        )";
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
                                                        '$est_nombre',
                                                        $checkPortadaDescripcion
                                                )";
        
                                                }
       // echo $sql2;
        $result=mysql_query($sql2);
        if(!$result)
                     $error=1;
        
        

	$_SESSION["ord_id"] = $ord_id;
        
        /* UPDATEAR PLAZO DE PROVEEDOR */
        /*
        if ($est_id == 2){
        $sql = "UPDATE ordenes SET ord_plazo_proveedor = '$fecha' where ord_id = $ord_id";}
        if ($est_id == 10){
        $sql = "UPDATE ordenes SET ord_plazo = '$fecha' where ord_id = $ord_id"; 
             } */
        echo 'aca:',$est_id;
        switch ($est_id) {
    case 2://estadoEnviadoProveedor
    {$sql = "UPDATE ordenes SET ord_plazo_proveedor = '$fecha' where ord_id = $ord_id";$result=mysql_query($sql);echo $sql; break;}
    case 10://estadoConfirmarProveedor
    { $sql = "UPDATE ordenes SET ord_plazo = '$fecha' where ord_id = $ord_id"; $result=mysql_query($sql);echo $sql; break;}
    case 11://estadoFinalizadoPendienteFacturacion
    {$sql = "UPDATE ordenes SET fecha_pendiente_facturacion = '$fecha' where ord_id = $ord_id"; $result=mysql_query($sql); echo $sql;break;}
    case 3://estadoAprobadoBajoCosto
    { $sql = "UPDATE ordenes SET fecha_aprobado_bajocosto = '$fecha' where ord_id = $ord_id"; $result=mysql_query($sql);echo $sql;break;}
}
        
        

         if(!$result)
                     $error=1;
        
        
        
        
                      if($error) 
                      {
                            mysql_query("ROLLBACK");
                            echo "Error en la transaccion";
                             mysql_close();
                          //  header("location:ver-alta-clientes.php?action=4");
                        } 
                        else 
                        {
                        mysql_query("COMMIT");
                         mysql_close();
                        echo "Transacción exitosa"; echo $estado_id=$_SESSION['est_id'];
                              header("location:ver-alta-ordenes.php?action=2&est_id=$estado_id_filtro&prv_id=$prov_filtro"); 
                        }


?>