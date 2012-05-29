<?php


/*$con = mysql_connect("localhost:3306","root","");
  if (!$con)
  {
    echo '<b>Could not connect.</b>';
    die(mysql_error()); // TODO: better error handling
  }
  else
  {
      //echo "CONECTOOOO";
      $conecto = mysql_select_db("semantec", $con);
      
      if ($conecto)
          echo "SELECCIONO BD";
      else
          echo "NO PASA NADA";
*/

    include ("conexion.php");

//$colour = 'red';

    //$idFile = 5;
    //$idOrden = $_POST["ord_id"];
    $idOrden = $_GET["ord_id"];
    $items   = $_GET["items"];
    //$idOrden = 22;
    //echo $idOrden;
    //$fecha  = 'NOW()';
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
        "VALUES ('$fileName', '$fileSize', '$fileType', '$content','detalles_ordenes')";

        mysql_query($sql_file);
        echo "<br>File $fileName uploaded<br>";
        $idFile = mysql_insert_id();
        
                                                        }
    
        
    if ($idFile != -1)
    $query = "INSERT INTO factura_venta (files_id,ord_id,fav_fecha,estado) VALUES ($idFile,$idOrden,NOW(),$estado)";
    else
    $query = "INSERT INTO factura_venta (ord_id,fav_fecha,estado) VALUES ($idOrden,NOW(),1)";
        //$query = "CALL usp_registrar_factura($idFile,$idOrden,NOW(),$estado,$adjunta)";
        $inserto = mysql_query($query);
        echo "EL QUERY ES: ".$query;
        if ($inserto)
            echo "INSERTO PROCEDURO";
        else
            echo "INSERTO PROCEDURO";
        
        $nro_factura = mysql_insert_id();
        
       echo "ITEMS : ".$items; 
       
       $i=0;
        do{
            $columnaDesc = "txtDescripcion".$i;
            $columnaPrec = "txtPrecio".$i;
            $descripcion = $_POST[$columnaDesc];
            $precio = $_POST[$columnaPrec];    
            $query = "INSERT INTO detalle_factura_venta (iva_idiva,factura_venta_fav_id,det_fav_descripcion,det_fav_precio) VALUES (2,$nro_factura,$descripcion,$precio)";
            mysql_query($query);
            $i++;
        }while($i < $items);
        

            //$sth = $dbh->prepare('CALL usp_registrar_factura(?,?,?,?,?)');
            //$sth->bindParam(1, $idFile, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 11);
            //$sth->bindParam(2, $idOrden, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 11);
            //$sth->bindParam(3, $fecha, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 20);
            //$sth->bindParam(4, $estado, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 11);
            //$sth->bindParam(5, $adjunta, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 11);
            //$sth->execute();
            mysql_close();
            
  //}

?>
