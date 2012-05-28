<?php
    include("validar.php");

    $action = $_GET["action"]; // 0
    if($action == 0){
          $titulo = "Datos de Orden de Servicio";
          $ord_id = $_GET["ord_id"];
    }
    else if($action == 1){
          $titulo = "Se ha dado de alta la siguiente Orden de Servicio";
          $ord_id = $_SESSION["ord_id"];
    }
    else{ // 2
          $titulo = "Se han modificado los datos del la siguiente Orden de Servicio"; 
          $ord_id = $_SESSION["ord_id"];
    }
        include("funciones.php");

        
        include("conexion.php");
        
        
        $sql0 = "SELECT ord_codigo, ord_descripcion, cli_id, prv_id, est_id, ord_alta, ord_plazo, ord_costo, ord_venta
                  FROM ordenes WHERE ord_id = $ord_id";
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);  // datos de la orden
        $cli_id = $fila0["cli_id"];
        $prv_id = $fila0["prv_id"];
        $est_id = $fila0["est_id"];

        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE cli_id = $cli_id"; // datos de cliente
        $resultado1 = mysql_query($sql);
        $fila1 = mysql_fetch_array($resultado1);

        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE prv_id = $prv_id"; //datos del proveedor
        $resultado2 = mysql_query($sql);
        $fila2 = mysql_fetch_array($resultado2);

        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_id"; //datos del estado
        $resultado3 = mysql_query($sql);
        $fila3 = mysql_fetch_array($resultado3);
        
        $pendienteFacturacion = 11;
        

?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.datepick.js"></script>
  <script type="text/javascript" src="js/jquery.datepick-es.js"></script>
  <script type="text/javascript">
  $(function() {
      $('#ord_plazo').datepick();
  });
  </script>    

  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec-logo.jpg" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">


      <h2>Panel de control</h2>


      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2" align="center"> <?php echo($titulo)?> </td>
            <td width="89" align="center">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td width="157">C&oacute;digo de Orden</td>
            <td width="309"><?php echo($fila0["ord_codigo"]);?></td>
            <td rowspan="8" align="center"><a href="ver-generar-factura.php?ord_id=<?php echo($ord_id)?>"><img src="images/generarFactura2.png" /></a><br><b>Generar factura</b></br></td>
          </tr>
          <tr>
            <td>Descripci&oacute;n de Orden</td>
            <td><?php echo(nl2br(utf8_encode($fila0["ord_descripcion"])));?></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>
                <?php echo($fila1["cli_nombre"]);?>            </td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
                <?php echo($fila2["prv_nombre"]); ?>            </td>
          </tr>
          <tr>
            <td>Estado</td>
            <td>
                <?php echo(utf8_encode($fila3["est_nombre"])); ?>            </td>
          </tr>
          <tr>
            <td>Plazo de Finalización</td>
            <td><?php echo(tfecha($fila0["ord_plazo"]));?></td>
          </tr>          <tr>
            <td>Valor Costo de la Orden</td>
            <td><?php echo($fila0["ord_costo"]);?></td>
            </tr>          
          <tr>
            <td>Valor Venta de la Orden</td>
            <td><?php echo($fila0["ord_venta"]);?></td>
          </tr>
          <tr>
            <td><a href="form-alta-ordenes.php">
               <input type="button" value="Agregar otra orden" class="botones" />
            </a></td>
          <td>&nbsp;&nbsp; 
                <a href="form-alta-ordenes.php"></a>            <a href="lista-ordenes.php">
                <input type="button" value="Ir al Listado" class="botones" />
                </a><a href="form-edit-ordenes.php?ord_id=<?php echo($ord_id)?>">
                <input type="button" value="Modificar datos" class="botones" />
                </a></td>
            <td></td>
          </tr>          
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
          </tr>
      </table>
      
     <div class="clear"></div>

   </div>
   <!--end contenedor-->



  </div>
   <!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main -->
   
   <!--start footer-->
   <footer>
<?php
    include("footer.php");
?>
   </footer>
   <!--fin footer-->

   </body>
</html>