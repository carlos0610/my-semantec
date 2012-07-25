<?php
    include ("funciones.php");
    include ("conexion.php");

    /* FALTA TRANSACCION */
    $idOrden = $_GET["ord_id"];
    $items   = $_GET["items"];
    $nota    = $_POST["txtNota"];
    $iva     = $_POST["comboIva"];   
    $Remito= $_POST["txtRemito"]; 
    $vencimiento = gfecha($_POST["vencimiento"]);
    $condicion_venta= $_POST["condicion_venta"];
    
    
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
        $query = "INSERT INTO factura_venta (files_id,ord_id,fav_fecha,fav_nota,    fav_remito, fav_condicion_vta ,fav_vencimiento,   estado) VALUES ($idFile,$idOrden,NOW(),'$nota',$Remito,'$condicion_venta','$vencimiento',$estado)";
    else
        $query = "INSERT INTO factura_venta (ord_id,fav_fecha,fav_nota,  fav_remito, fav_condicion_vta ,fav_vencimiento, estado) VALUES ($idOrden,NOW(),'$nota',$Remito,'$condicion_venta','$vencimiento',1)";
        $inserto = mysql_query($query);      
        $nro_factura = mysql_insert_id();
        ECHO $query;
        
       
       $i=1;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            
        while(($i <= $items)&($descripcion != '')){   
            $query = "INSERT INTO detalle_factura_venta (fav_id,iva_idiva,det_fav_descripcion,det_fav_precio) VALUES ($nro_factura,$iva,'$descripcion',$precio)";
            mysql_query($query);
            
            $i++;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
         //   echo  $query;
        };

            mysql_close();

  header("location:ver-alta-factura.php?ord_id=$idOrden&fav_id=$nro_factura");

?>
