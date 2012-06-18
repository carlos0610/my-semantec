<?php

    include ("conexion.php");


    
    $cantidadCheckboxs = $_GET["cant"];
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
        
        
        $sql_grupo_ordenes= "INSERT INTO `grupo_ordenes`(`gru_fecha_alta`) VALUES (NOW()) ";
        mysql_query($sql_grupo_ordenes);
        $id_grupo_ordenes = mysql_insert_id(); 
        
        //actualizo las ordenes
        $i=0;
        echo $sql_grupo_ordenes;
        while($i<$cantidadCheckboxs)
        {
            $i++;
            $ord_id=$_POST["ordenCheck$i"];
            $sql="UPDATE `ordenes` 
                  SET `gru_id_venta`=$id_grupo_ordenes
                  WHERE `ord_id`= $ord_id
                ";
            echo $sql;
            mysql_query($sql);
        }
        
        
        
    if ($idFile != -1)
        $query = "INSERT INTO factura_compra (files_id,idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota,gru_id) VALUES ($idFile,$iva,$prv_id,NOW(),$estado,'$descripcion',$subtotal,$percepciones,'$nota',$id_grupo_ordenes)";
    else
        $query = "INSERT INTO factura_compra (idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota,gru_id) VALUES ($iva,$prv_id,NOW(),$estado,'$descripcion',$subtotal,$percepciones,'$nota',$id_grupo_ordenes)";   
    
    $inserto = mysql_query($query);      
    $nro_factura = mysql_insert_id();
           $i=1;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            
        while(($i <= $cantidadCheckboxs)&($descripcion != '')){   
            $query = "INSERT INTO detalle_factura_compra (fco_id,det_fco_cant,det_fco_descripcion,fco_preciounitario) VALUES ($nro_factura,$iva,'$descripcion',$precio)";
            mysql_query($query);
            
            $i++;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            echo  $query;
        };
    
    
    
    
    
    echo "NUESTRO QUERY: ".$query;
    
    
    mysql_close();

  //header("location:ver-alta-factura.php?ord_id=$idOrden&fav_id=$nro_factura");

?>