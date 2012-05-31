<?php

    include ("conexion.php");


    
    
    $descripcion = $_POST["txtDescripcionItem1"];
    $subtotal = $_POST["txtSubtotal"];
    $nota    = $_POST["txtNota"];
    $iva     = $_POST["comboIva"];
    $percepciones = $_POST["txtPercepciones"];
    
    
    //$Remito= $_POST["txtRemito"]; 
    //$condicion_venta= $_POST["condicion_venta"]; 
    
    $prv_id = $_GET["prv_id"];
    $estado = 1;
    $idFile = -1;
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','factura_venta')";

        mysql_query($sql_file);
        $idFile = mysql_insert_id(); // HAY QUE VALIDAR SI SE ROMPE LA TRANSACCIÓN DEL FILE
        
        
        
                                                        }
    
        
    if ($idFile != -1)
        $query = "INSERT INTO factura_compra (files_id,iva_idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota) VALUES ($idFile,$iva,$prv_id,NOW(),$estado,'$descripcion',$subtotal,$percepciones,'$nota')";
    else
        $query = "INSERT INTO factura_compra (iva_idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota) VALUES ($iva,$prv_id,NOW(),$estado,$descripcion,$subtotal,$percepciones,$nota)";
  
    
    
    $inserto = mysql_query($query);      
    $nro_factura = mysql_insert_id();
    
    echo "NUESTRO QUERY: ".$query;
    
    
    mysql_close();

  //header("location:ver-alta-factura.php?ord_id=$idOrden&fav_id=$nro_factura");

?>