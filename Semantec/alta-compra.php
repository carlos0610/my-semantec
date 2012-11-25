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
    
            
        $error = 0; //variable para detectar error
        mysql_query("BEGIN"); // Inicio de Transacción
    
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

        $result=mysql_query($sql_file);
        if(!$result)
                     $error=1;
        $idFile = mysql_insert_id(); // HAY QUE VALIDAR SI SE ROMPE LA TRANSACCIÓN DEL FILE
        }
        

        
        //actualizo las ordenes
        $i=0;
        
    if ($idFile != -1)
        $query = "INSERT INTO factura_compra (files_id,idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota) VALUES ($idFile,1,$prv_id,NOW(),$estado,'$descripcion',$subtotal,$percepciones,'$nota')";
    else
        $query = "INSERT INTO factura_compra (idiva,prv_id,fco_fecha,estado,fco_descripcion,fco_subtotal,fco_percepcion,fco_nota) VALUES (1,$prv_id,NOW(),$estado,'$descripcion',$subtotal,$percepciones,'$nota')";   
    
    $prueba = $query;
    
    $inserto = mysql_query($query);  
    if(!$inserto){
                     $error=1; echo "Error en : $query";
    }
    $nro_factura = mysql_insert_id();
           $i=1;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            
        while(($i <= $cantidadCheckboxs)&($descripcion != '')){   
            
            $query = "INSERT INTO detalle_factura_compra (fco_id,det_fco_descripcion, det_fco_preciounitario) VALUES ($nro_factura,'$descripcion',$precio)";
            $result=mysql_query($query);
            if(!$result){
                     $error=1; echo "Error en : $query";}
            $i++;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
           // echo  $query;
        };
    
     //echo "NUESTRO QUERY: ".$prueba;
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
                             header("location:ver-alta-factura-compra.php?fav_id=$nro_factura");     
                        }
    



?>