<?php
$titulo = "Alerta de ordenes con vencimiento.";
        include("validar.php");
        include("funciones.php");
        
        include("conexion.php");
        
        $sql0 =    "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 2
		    AND DATEDIFF(ord_plazo_proveedor,now()) = 1 
                    ORDER BY o.ord_alta DESC";
        $alerta_plazo_proveedor = mysql_query($sql0);
        
        
        $tamPag=10;
        include("paginado.php");
        $sql = "SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre, prv_nombre, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 2
		    AND DATEDIFF(ord_plazo_proveedor,now()) = 1 
                    ORDER BY o.ord_alta DESC";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        
?>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>  
      
  </head>
  <body>
<div id="contenedor" style="height:auto;">
  <?php  if ($cantidad>0) {?>
    <div id="mensaje" style="height:auto;">
  
      <table width="100%" border="0">
      <tr>
        <td width="18%"><div align="right"><img src="images/warning.png" width="48" height="48"></div></td>
        <td width="82%"><h2>Las siguientes ordenes  aún no recibieron respuesta del proveedor</h2></td>
      </tr>
    </table>
    </div>
    
    
<table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="70">C&oacute;digo</td>
            <td width="100">Fecha alta orden</td>
            <td width="100">Cliente</td>
            <td>Descripci&oacute;n</td>
            <td width="100">Proveedor</td>
            <td width="100">Estado</td>
            <td width="32">Respuesta proveedor</td>
            <td width="32">&nbsp;</td>
            <td width="32">&nbsp;</td>            
            <td width="32">&nbsp;</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($alerta_plazo_proveedor)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["ord_codigo"]);?></td>
            <td><?php echo(tfecha($fila["ord_alta"]));?></td>
            <td><?php echo($fila["cli_nombre"]);?></td>
            <td><?php echo(nl2br(utf8_encode($fila["ord_descripcion"])));?></td>
            <td><?php echo($fila["prv_nombre"]);?></td>
            <td>
                  <img src="images/estado.png" alt="estado" style="background-color:<?php echo($fila["est_color"]);?>">
                  <?php echo(utf8_encode($fila["est_nombre"]));?>
            </td>
            <td style="background-color: red;"><?php echo(tfecha($fila["ord_plazo_proveedor"]));?></td>
            <td width="32"><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=0"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="form-edit-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>"><img src="images/editar.png" alt="editar" title="Modificar orden" width="32" height="32" border="none" /></a></td>
            <td><a href="#" onClick="eliminarOrden(<?php echo($fila["ord_id"]);?>,'<?php echo($fila["ord_codigo"]);?>')">
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
    
    <?php } else { echo "<img src=images/ok.png> SIN NOVEDADES";}?>
    
    
    
    
    
    
      <div class="clear"></div>
  </div>

</body>
</html>