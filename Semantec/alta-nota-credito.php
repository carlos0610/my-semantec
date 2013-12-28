<?php
    include ("funciones.php");
    include("validar.php");
    include ("conexion.php");
    include ("Modelo/modeloAbonosDetalle.php");
    include ("Modelo/modeloOrdenes.php");
    include ("Modelo/modeloAbonoDeOrden.php");
    include ("Modelo/modeloFacturaVenta.php");
    
    $cantidadCheckboxs = $_POST["cantidadOrdenesAceptadas"]; 
    //echo $cantidadCheckboxs;
    $items   = $_GET["items"];
    $cli_id  = $_GET["cli_id"];
    $nota    = $_POST["txtNota"];
    $iva     = $_POST["comboIva"];   
    $usu_id     = $_SESSION["usu_id"];
    $totalFav =$_POST["totalOrdenVentatxt"];
    $valorNC = $_POST["txtTotalItem1"];
    //$vencimiento = gfecha($_POST["vencimiento"]);
    
    $codNotaCredito="0001-";
    $codNotaCredito.= trim($_POST["cod_notaOculto"]); 
    $notac_fecha = gfecha($_POST["fechaaltaOculto"]);       
 
    
    
        $error = 0; //variable para detectar error
        
        // Inicio de Transacción
        mysql_query("BEGIN"); 
    
        
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','nota_credito')";
        $result=mysql_query($sql_file);
        if(!$result)
           $error=1;
        $idFile = mysql_insert_id(); // HAY QUE VALIDAR SI SE ROMPE LA TRANSACCIÓN DEL FILE 
       }
       
       
       
        $sql_grupo_nota= "INSERT INTO `grupo_fav_nota`(`gfn_fecha_alta`) VALUES (NOW()) ";
        $result=mysql_query($sql_grupo_nota);
        if(!$result)
                     $error=1;
        $id_grupo_notacredito = mysql_insert_id(); 
        
        //Le indico a la factura de venta un codigo de grupo de nota de credito
        $i=0;

        while($i<$cantidadCheckboxs)
        {
            $i++; 
            //echo $_POST["ordenCheck$i"];
            
            $fav_id=$_POST["ordenCheck$i"];
            
            $sql="UPDATE `factura_venta` 
                  SET 
                `grupo_nota_credito`=$id_grupo_notacredito";
                 
            $sql.=" WHERE `fav_id`= $fav_id";
           // echo 'UPDATE DE FACTURAS: ',$sql;
            $result=mysql_query($sql);
            if(!$result){
                     $error=1; 
                     }
        }
        echo "valor NC: ",$valorNC;
        echo "  TOTAL FAV: ",$totalFav;
        echo "  DIFERENCIA : ",number_format(($totalFav-$valorNC), 2);
        echo "  cantidad de Fav: ",$cantidadCheckboxs;
        if ((number_format(($totalFav-$valorNC), 2)) ==0)
        {   
            $t=0;
            while($t<$cantidadCheckboxs)
            {   $t++; 
                $fav_id=$_POST["ordenCheck$t"];  echo "fav id:",$fav_id;
                $gru_id=getFavgru_idWhitID($fav_id);
                echo " gruID: ",$gru_id;
                if(!(updateOrdenesEstadoFinalizadoPendienteFac($gru_id)))
                  $error=1;
            }
        }   
        
    if ($idFile != -1)
        {
        $query = "INSERT INTO nota_credito (nrc_codigo,idiva,nrc_fecha,gfn_id,nrc_nota,usu_id,files_id,cli_id) VALUES ('$codNotaCredito',$iva,'$notac_fecha',$id_grupo_notacredito,'$nota',$usu_id,$idFile,$cli_id)";       
     }else
        {
        $query = "INSERT INTO nota_credito (nrc_codigo,idiva,nrc_fecha,gfn_id,nrc_nota,usu_id,cli_id) VALUES ('$codNotaCredito',$iva,'$notac_fecha',$id_grupo_notacredito,'$nota',$usu_id,$cli_id)";  
        }
   //     echo $query;
       $inserto = mysql_query($query); 
       
       
       if(!$inserto)
       $error=1;
                     
                     
        $nro_nota_credito = mysql_insert_id();
        
        
        $i=1;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
            
        while(($i <= $items)&($descripcion != '')){   
            $query = "INSERT INTO detalle_nota_credito (idiva,nrc_id,det_nrc_descripcion,det_nrc_precio) VALUES ($iva,$nro_nota_credito,'$descripcion',$precio)";
            $result=mysql_query($query);
            if(!$result)
                     $error=1;
            $i++;
            $columnaDesc = "txtDescripcionItem".$i;
            $columnaPrec = "txtTotalItem".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec]; 
        //    echo  $query;
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
                              header("location:ver-alta-nota-credito.php?nrc_id=$nro_nota_credito");   
                        }


           

?>

