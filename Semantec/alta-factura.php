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

    $idFile = 5;
    //$idOrden = $_SESSION["ord_id"];
    
    $idOrden = 22;
    //echo $idOrden;
    //$fecha  = 'NOW()';
    $estado = 1;
    $adjunta= 1;
        
    
    
        $query = "CALL usp_registrar_factura($idFile,$idOrden,NOW(),$estado,$adjunta)";
        $inserto = mysql_query($query);
        echo "EL QUERY ES: ".$query;
        if ($inserto)
            echo "INSERTO PROCEDURO";
        else
            echo "INSERTO PROCEDURO";

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
