<?php
    $titulo = "Listado de Facturas.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
/* CALCULO PAGINADO */  ###############################################################################
    $sql0 = "SELECT f.ord_id, fav_id, fav_fecha, cli_nombre, prv_nombre, files_id
                  FROM factura_venta f, ordenes o, clientes c, proveedores p
                  WHERE f.ord_id = o.ord_id
                    AND o.prv_id = p.prv_id  
                    AND o.cli_id = c.cli_id
                    AND f.estado = 1 
                    ORDER BY f.fav_fecha DESC 
                    ";
    $tamPag=10;
    
    include("paginado.php"); 
    
        $sql = "SELECT f.ord_id, fav_id, fav_fecha, cli_nombre, prv_nombre, files_id
                  FROM factura_venta f, ordenes o, clientes c, proveedores p
                  WHERE f.ord_id = o.ord_id
                    AND o.prv_id = p.prv_id  
                    AND o.cli_id = c.cli_id
                    AND f.estado = 1 
                    ORDER BY f.fav_fecha DESC
                    ";
                $sql .= " LIMIT ".$limitInf.",".$tamPag; 
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
?>
<!doctype html>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>  
      
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
      <h2>Panel de control - Listado de Facturas</h2>

      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="70">C&oacute;digo</td>
            <td width="100">Fecha</td>
            <td width="100">Cliente</td>
            <td width="100">Proveedor</td>
            <td width="32">Archivo</td>            
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["fav_id"]);?></td>
            <td><?php echo(tfecha($fila["fav_fecha"]));?></td>
            <td><?php echo($fila["cli_nombre"]);?></td>
            <td><?php echo($fila["prv_nombre"]);?></td>
                <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila_req["files_id"] ?>
            <td width="60"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png /></a>";?></td>
            <td width="32"><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=0"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="#" onclick="eliminarFactura(<?php echo($fila["fav_id"]);?> )">
                <img src="images/eliminar.png" alt="eliminar" title="Eliminar orden" width="32" height="32" border="none" /></a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
          <tr>
            <td colspan="8" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
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
