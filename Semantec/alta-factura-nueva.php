<?php
    include ("funciones.php");
    include ("conexion.php");

    
    $cantidadCheckboxs = $_POST["cantidadOrdenesAceptadas"];
    $items   = $_GET["items"];
    $nota    = $_POST["txtNota"];
    $iva     = $_POST["comboIva"];   
    $Remito= $_POST["txtRemito"]; 
    $vencimiento = gfecha($_POST["vencimiento"]);
    $condicion_venta= $_POST["condicion_venta"];
    $codFactura="0001-";
    $codFactura.= $_POST["codFactura"]; echo $codFactura;
    
    $fav_fecha = gfecha($_POST["fechaalta"]);// MOMENTANEO 
    
        $error = 0; //variable para detectar error
        mysql_query("BEGIN"); // Inicio de Transacción
    
    $estado = 1;
    $idFile = -1;
    if($_FILES['userfile']['size']>0)
    {      //SI ELIGIO UN ARCHIVO
                                
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','factura_venta')";
        $result=mysql_query($sql_file);
        if(!$result)
                     $error=1;
        $idFile = mysql_insert_id(); // HAY QUE VALIDAR SI SE ROMPE LA TRANSACCIÓN DEL FILE 
       }
       
        $sql_grupo_ordenes= "INSERT INTO `grupo_ordenes`(`gru_fecha_alta`) VALUES (NOW()) ";
        $result=mysql_query($sql_grupo_ordenes);
        if(!$result)
                     $error=1;
        $id_grupo_ordenes = mysql_insert_id(); 
        
        //actualizo las ordenes
        $i=0;

        while($i<$cantidadCheckboxs)
        {
            $i++;
            $ord_id=$_POST["ordenCheck$i"];
            $sql="UPDATE `ordenes` 
                  SET `gru_id`=$id_grupo_ordenes,`est_id`=12 
                  WHERE `ord_id`= $ord_id
                ";
            mysql_query($sql);
        }
                                                   
    if ($idFile != -1)
        {
        $query = "INSERT INTO factura_venta (gru_id, files_id,fav_fecha,fav_nota,fav_remito, fav_condicion_vta ,fav_vencimiento,   estado,cod_factura_venta) VALUES ($id_grupo_ordenes, $idFile,'$fav_fecha','$nota',$Remito,'$condicion_venta','$vencimiento',$estado,'$codFactura')";       
     }else
        {
        $query = "INSERT INTO factura_venta (gru_id,fav_fecha,fav_nota,  fav_remito, fav_condicion_vta ,fav_vencimiento, estado,cod_factura_venta) VALUES ($id_grupo_ordenes,'$fav_fecha','$nota',$Remito,'$condicion_venta','$vencimiento',1,'$codFactura')";  
        }
        echo $query;
       $inserto = mysql_query($query); 
       if(!$inserto)
                     $error=1;
        $nro_factura = mysql_insert_id();
       $i=1;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            
        while(($i <= $items)&($descripcion != '')){   
            $query = "INSERT INTO detalle_factura_venta (fav_id,idiva,det_fav_descripcion,det_fav_precio) VALUES ($nro_factura,$iva,'$descripcion',$precio)";
            mysql_query($query);
            
            $i++;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            echo  $query;
        };
        
        
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
                        echo "Transacción exitosa";
                              header("location:ver-alta-factura.php?fav_id=$nro_factura");   
                        }


           

?>
