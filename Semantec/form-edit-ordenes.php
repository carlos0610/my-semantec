<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de modificaci&oacute;n de una Orden de Servicio.";
        include("validar.php");
        include("funciones.php");

        include("conexion.php");
        $ord_id = $_GET["ord_id"];
        $sql0 = "SELECT ord_codigo, ord_descripcion, cli_id, prv_id, est_id, ord_alta, ord_plazo, ord_costo, ord_venta 
                    FROM ordenes 
                    WHERE ord_id = $ord_id";
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);

        $sql = "SELECT  cli_id, cli_nombre FROM clientes";
        $resultado1 = mysql_query($sql);
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores";
        $resultado2 = mysql_query($sql);
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        $resultado3 = mysql_query($sql);
        // busqueda para  imprimir el nombre del estado.
	$est_id= $fila0["est_id"];		
	$sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id = $est_id"; //datos del estado
        $resultado4 = mysql_query($sql);
        $fila4 = mysql_fetch_array($resultado4);

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
  <script type="text/javascript" src="js/funciones.js"></script>
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

      <form action="edit-ordenes.php" method="post" id="frm" >
          
      <input type="hidden" value="<?php echo($fila0["ord_id"]); ?>" name="ord_id2" >
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
            <td>C&oacute;digo de Orden</td>
            <td><input type="number" value="<?php echo($fila0["ord_codigo"]); ?>" class="campos" id="ord_codigo" name="ord_codigo" min="0" required/></td>
            <td></td>
          </tr>
          <tr>
            <td>Descripci&oacute;n de Orden</td>
            <td><textarea class="campos" id="ord_descripcion" name="ord_descripcion" rows="9" required><?php echo(utf8_encode($fila0["ord_descripcion"])); ?></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>
                <select name="cli_id" id="cli_id" class="campos">
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"<?php if($fila0["cli_id"]==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo($fila["cli_nombre"]); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
                <select name="prv_id" id="prv_id" class="campos">
    <?php
          while($fila2 = mysql_fetch_array($resultado2)){
    ?>
                    <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($fila0["prv_id"]==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo($fila2["prv_nombre"]); ?></option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Estado</td>
            <td>
                 <?php echo($fila4["est_nombre"]); ?> 
                <input type="hidden" value="<?php echo($est_id); ?>" name="est_id" id="est_id" />
                <a href="form-alta-ordenes-detalle.php?ord_id=<?php echo($ord_id); ?>"><input type="button" value="Cambiar" class="botones" /></a> &nbsp; &nbsp; 
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Plazo de Finalización</td>
            <td><input type="text" value="<?php echo(mfecha($fila0["ord_plazo"])); ?>" class="campos" id="ord_plazo" name="ord_plazo" /></td>
            <td></td>
          </tr>          <tr>
            <td>Valor Costo de la Orden</td>
            <td><input type="number" value="<?php echo($fila0["ord_costo"]); ?>" class="campos" id="ord_costo" name="ord_costo" required /></td>
            <td></td>
          </tr>          <tr>
            <td>Valor Venta de la Orden</td>
            <td><input type="number" value="<?php echo($fila0["ord_venta"]); ?>" class="campos" id="ord_venta" name="ord_venta"  required/></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar Orden" class="botones" />
                <input type="hidden" value="<?php echo($ord_id); ?>" name="ord_id" id="ord_id" />
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