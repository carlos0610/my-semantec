<?php
    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de modificaci&oacute;n de una Orden de Servicio.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        $ord_id = $_GET["ord_id"];
        $sql0 = "SELECT ord_codigo, ord_descripcion, o.cli_id,c.cli_nombre,c.sucursal, prv_id, est_id, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta 
                    FROM ordenes o,clientes c 
                    WHERE ord_id = $ord_id
                    AND o.cli_id = c.cli_id";
        $resultado0 = mysql_query($sql0);
        $fila0 = mysql_fetch_array($resultado0);
        //Se quita temporalmente el select de cliente/sucursal hasta fixearlo para que funcione en el edit
        /*$sql = "SELECT sucursal_id,sucursal,cli_id,cli_nombre,p.nombre as provincia 
                FROM clientes,ubicacion u,provincias p, partidos pa,localidades l
                WHERE 
                clientes.ubicacion_id = u.id
                AND u.provincias_id = p.id
                AND u.partidos_id = pa.id
                AND u.localidades_id = l.id
                AND clientes.estado = 1
                AND sucursal_id is null
                ORDER BY cli_nombre,provincia";
        $resultado1 = mysql_query($sql);*/ 
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1";
        $resultado2 = mysql_query($sql);
        //$sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        //$resultado3 = mysql_query($sql);
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
      $('#fecha').datepick();
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
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
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
            <td colspan="2"> <?php echo($titulo)?> </td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td>C&oacute;digo de Orden</td>
            <td>
                <?php echo($fila0["ord_codigo"]); ?>             </td>   
            <td>            </td>
          </tr>
  
          
          <tr>
            <td>Estado</td>
            <td><!--Guardo proveedor oculto y cambio de estado-->
                <form action="form-alta-ordenes-detalle.php?ord_id=<?php echo($ord_id); ?>&ord_costo=<?php echo($fila0["ord_costo"]); ?>&ord_venta=<?php echo($fila0["ord_venta"]); ?>&cli_id=" method="post" id="frm" >
                 <?php echo(utf8_encode($fila4["est_nombre"])); ?> 
                <input type="hidden" value="<?php echo($fila0["prv_id"]); ?>" name="provedor_id" id="provedor_id" />
                <input type="submit" value="Cambiar" class="botones" id="botonAgregar" style="visibility:<?php if($fila0["prv_id"]==1){echo "hidden";}else{ echo "visible";} ?>"  />
                </form>            </td>
            <td></td>
          </tr>
          
          
        
          <tr>
            <form action="edit-ordenes.php" method="post" id="frm" >
              <input type="hidden" value="<?php echo($fila0["ord_codigo"]); ?>" class="campos" style="text-align:right" id="ord_codigo" name="ord_codigo" min="0" required/>  
             <input type="hidden" value="<?php echo($est_id); ?>" name="est_id" id="est_id" />
             <input type="hidden" value="<?php echo($fila0["ord_id"]); ?>" name="ord_id2" >
            <td>Descripci&oacute;n de Orden</td>
            <td><textarea class="campos" id="ord_descripcion" name="ord_descripcion" rows="9" required><?php echo(utf8_encode($fila0["ord_descripcion"])); ?></textarea></td>
            <td></td>
          </tr>
          <tr>
            <td>Cliente</td>
            <td>
              <?php echo($fila0["cli_nombre"]); ?>(<?php echo($fila0["sucursal"]); ?>)             
            </td>
            <td></td>
          </tr>
          <tr>
            <td>Proveedor</td>
            <td>
                <select name="prv_id" id="prv_id" class="campos" onChange="return VerificarProveedor();">
    <?php
          while($fila2 = mysql_fetch_array($resultado2)){
    ?>
                    <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($fila0["prv_id"]==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>            </td>
            <td></td>
          </tr>
          <?php if ($fila0["est_id"] ==  2){ ?>  
          <tr>
              <td><label id="texto_respuesta"><?php echo "Fecha respuesta proveedor" ?></label></td>
              <td><input type="text" class="campos" id="fecha" name="fecha" value="<?php echo mfecha($fila0["ord_plazo_proveedor"])?>"/></td>
            <td></td>
          </tr>
          
          <?php  } else if ($fila0["est_id"] ==  9) { ?>
          
          <tr>
              <td><label id="texto_respuesta"><?php echo "Plazo de finalización" ?></label></td>
            <td><input type="text" class="campos" id="fecha" name="fecha" value="<?php echo mfecha($fila0["ord_plazo"])?>"/></td>
            <td></td>
          </tr>
          <?php } ?>
          
           <tr>
            <td>Valor Costo de la Orden</td>
            <td><input type="number" style="text-align:right" value="<?php echo($fila0["ord_costo"]); ?>" class="campos" id="ord_costo" name="ord_costo" required /></td>
            <td></td>
          </tr>          <tr>
            <td>Valor Venta de la Orden</td>
            <td><input type="number" style="text-align:right" value="<?php echo($fila0["ord_venta"]); ?>" class="campos" id="ord_venta" name="ord_venta"  required/></td>
            <td></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <a href="lista-ordenes.php"><input type="button" value="Ir al Listado" class="botones" /></a> &nbsp; &nbsp; 
                <input type="reset" value="Restablecer" class="botones" /> &nbsp; &nbsp; 
                <input type="submit" value="Modificar Orden" class="botones" />
                <input type="hidden" value="<?php echo($ord_id); ?>" name="ord_id" id="ord_id" />            </td>
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