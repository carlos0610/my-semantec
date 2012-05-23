<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de cambio de estado de una Orden de Servicio.";
        include("validar.php");

        include("conexion.php");

		
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores";
        $resultado2 = mysql_query($sql);
		$proveedor = mysql_fetch_array($resultado2);
		
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        $resultado3 = mysql_query($sql);
        
        $orden_id=$_GET['ord_id'];
        
        $sql0 = "SELECT ord_codigo, ord_descripcion, cli_id, prv_id, est_id, ord_alta, ord_plazo, ord_costo, ord_venta 
                    FROM ordenes 
                    WHERE ord_id = $orden_id";       
        $resultado0 = mysql_query($sql0);       
        $fila0 = mysql_fetch_array($resultado0);
        $cliente_id=$fila0["cli_id"]; 
        
        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE cli_id= $cliente_id"; 
        $resultado1 = mysql_query($sql);
        $cliente = mysql_fetch_array($resultado1);
        
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
      <form action="alta-ordenes-detalle.php" method="post">
          
      <input type="hidden" value="<?php echo $orden_id; ?>" name="ord_id"  id="ord_id">
      <table class="forms" cellpadding="5">
          <tr class="titulo">
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
          <tr>
            <td>Descripci&oacute;n de Requerimiento</td>
            <td><textarea class="campos" id="ord_descripcion" name="ord_descripcion" rows="9"></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>                
				<?php echo($cliente["cli_nombre"]); ?> 
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
					<?php echo($proveedor["prv_nombre"]); ?> 
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Estado</td>
            <td>
                <select name="est_id" id="est_id" class="campos">
    <?php
          while($fila3 = mysql_fetch_array($resultado3)){
    ?>                 
                      <option style="background-color:<?php echo($fila3["est_color"]); ?>" value="<?php echo($fila3["est_id"]); ?>"<?php if($fila0["est_id"]==$fila3["est_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila3["est_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Fecha</td>
            <td><input type="text" class="campos" id="ord_plazo" name="ord_plazo" /></td>
            <td></td>
          </tr>
          <tr>
            <td>Monto</td>
            <td><input type="text" class="campos" id="ord_det_monto" name="ord_det_monto" /></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <a href="form-edit-ordenes.php?ord_id=<?php echo($orden_id)?>">
                <input type="button" value="  Volver   " class="botones" /></a> &nbsp; &nbsp;
                <input type="reset"  value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="  Guardar  " class="botones" />
            </td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
          </tr>
      </table> 
      </form>  
      
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