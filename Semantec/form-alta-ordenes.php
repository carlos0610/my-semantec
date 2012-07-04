<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de alta de una Orden de Servicio.";
        include("validar.php");
        include("conexion.php");
        $sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
           FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
           WHERE 
 	   clientes.ubicacion_id = u.id
           AND u.provincias_id = p.id
           AND u.partidos_id = pa.id
           AND u.localidades_id = l.id
           AND clientes.estado = 1
           AND sucursal_id is null
           ORDER BY cli_nombre,provincia";

        
        $resultado1 = mysql_query($sql);
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1";
        $resultado2 = mysql_query($sql);
        // BORRAR
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados WHERE est_id=1";
        $resultado3 = mysql_query($sql);
        $fila3 = mysql_fetch_array($resultado3);
        // ---

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
      $('#ord_alta').datepick();
  });
  </script>    
  <script type="text/javascript" src="js/validador.js"></script>
  <script type="text/javascript" src="js/select_dependientes_cliente_sucursal.js"></script>
  <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-ordenes.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
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
      
      <form action="alta-ordenes.php" method="post" enctype="multipart/form-data" enctype="multipart/form-data" >
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
            <td><input type="text"  style="text-align:right" class="campos" id="ord_codigo" name="ord_codigo" required />
                <span id="error" style="font-family: Verdana, Arial, Helvetica,sans-serif;font-size: 9pt;color: #CC3300;position:relative;visibility:hidden;">Orden existente</span>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Descripci&oacute;n de Orden</td>
            <td><textarea class="campos" id="ord_descripcion" name="ord_descripcion" rows="9" required ></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>
                <select name="cli_id" id="cli_id" class="campos" required onChange="cargaContenido(this.id)">
                    <option value='0'>Seleccione</option>;
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
    <?php
          }
    ?>
                </select>
            </td>
            <td></td>
          </tr>
          
          <tr>
            <td>Sucursal</td>
            <td>
                <select name="suc_id" id="suc_id" class="campos" required >
                <option value='0'>Seleccione</option>;    
                </select>
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
                <select name="prv_id" id="prv_id" class="campos" required>
    <?php
          while($fila2 = mysql_fetch_array($resultado2)){
    ?>
                    <option value="<?php echo($fila2["prv_id"]); ?>"><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
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
                <?php echo($fila3["est_nombre"]); ?>
                <input type="hidden" value=" <?php echo($fila3["est_id"]); ?>" name="est_id"  id="est_id">
                <input type="hidden" value=" <?php echo($fila3["est_nombre"]); ?>" name="est_nombre"  id="est_id">
            </td>
            <td></td>
          </tr>
          <tr><td>Fecha de alta</td>
              <td><input type="text" name="ord_alta" id="ord_alta" class="campos"></td>
          </tr>
          <td></td>
          <tr>
            <td>Valor Costo de la Orden</td>
            <td><input type="number" style="text-align:right" class="campos" id="ord_costo" name="ord_costo" value="0"  min="0" required OnKeyUp="return validarCostoDeLaOrden();" /></td>
            <td></td>
          </tr>          
          <tr>
            <td>Valor Venta de la Orden</td>
            <td><input type="number" style="text-align:right" class="campos" id="ord_venta" name="ord_venta" value="0"  min="0" required  OnKeyUp="return validarVentaDeLaOrden();" /></td>
            <td></td>
          </tr>
          <tr>
              <td>Adjuntar archivo</td><td>
                  <input type="file" class="" id="userfile" name="userfile" />
              </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Agregar Orden" class="botones" style="visibility:visible" id="botonAgregar" />
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