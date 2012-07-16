<?php
$titulo = "Alerta de ordenes sin enviar a proveedor.";
        include("validar.php");
        include("funciones.php");       
        include("conexion.php");
        
        
        
        $sql0 =    "SELECT ord_id, ord_codigo, u.usu_login,ord_descripcion, cli_nombre,c.sucursal,c.cli_id, prv_nombre,p.prv_id, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p,usuarios u
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 1
                    AND o.usu_id = u.usu_id";
        
                if(isset($_REQUEST['btnMostrar'])){
                        $id_usuario = $_GET["comboUsuarios"];
                        if ($id_usuario != 0)
                            $sql0 .= " AND o.usu_id = $id_usuario";
                        }
        $sql0 .=    " ORDER BY o.ord_alta DESC";
        mysql_query($sql0);
        
        
        $tamPag=10;
        include("paginado.php");
        $sql = "SELECT ord_id, ord_codigo, u.usu_login,ord_descripcion, cli_nombre,c.cli_id, prv_nombre,p.prv_id, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p,usuarios u
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 1
                    AND o.usu_id = u.usu_id";
                    if(isset($_REQUEST['btnMostrar'])){
                        $id_usuario = $_GET["comboUsuarios"];
                            if ($id_usuario != 0)
                                $sql .= " AND o.usu_id = $id_usuario";
                        }      
        $sql .=    " ORDER BY o.ord_alta DESC";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
        
        //echo "QUERY ".$sql;
        
        $alerta_orden_sinenviar = mysql_query($sql);
        $cantidad = mysql_num_rows($alerta_orden_sinenviar);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        
        $sql = "select usu_id,usu_login from usuarios";
        $resultado = mysql_query($sql);
        
      
?>
<html>  
  <head>
<?php
    include("encabezado-main.php");
?>  
  
    <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-alertas-sinenviar.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>       
  </head>
  <body>
      <div id="main" >
<div id="contenedor" style="height:auto;"  >
 
    <div id="mensaje" style="height:auto;">
        <form>
      <table width="100%" border="0">
      <tr>
        <td width="18%"><div align="right"><img src="images/warning.png" width="48" height="48"></div></td>
        <td width="82%"><h2>Las siguientes ordenes  se dieron de alta y aún no se enviaron al proveedor</h2></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><label></label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>Ver órdenes de
          <select name="comboUsuarios" id="comboUsuarios">
              <option value="0">Todos</option>
          <?php
          while($fila = mysql_fetch_array($resultado)){
                    ?>
          <option value="<?php echo($fila["usu_id"]); ?>"><?php echo(utf8_encode($fila["usu_login"])); ?></option>
          <?php
                                    }
                ?>
        </select>
          
          <input type="submit" name="btnMostrar" id="btnMostrar" value="Mostrar">
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
        </form>
  </div>
  <?php  if ($cantidad>0) {?>  
    <form id="filtro" name="filtro" action="lista-alertas-sinenviar.php" method="POST">
<table class="listadosALERTA" cellpadding="3" >
          <tr class="titulo">
            <td width="70">C&oacute;digo</td>
            <td width="100">Fecha alta orden</td>
            <td width="70">Creada por</td>
            <td width="160">Cliente</td>
            <td>Descripci&oacute;n</td>
            <td width="100">Proveedor</td>
            <td width="120">Estado</td>
            <td width="32">&nbsp;</td>
            <td width="32">&nbsp;</td>            
            <td width="32">
            </td>
          </tr>
   
          
  <?php
          while($fila = mysql_fetch_array($alerta_orden_sinenviar)){
              //echo($fila["ord_alta"]);
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["ord_codigo"]);?></td>
            <td><?php echo(tfecha($fila["ord_alta"]));?></td>
            <td><?php echo $fila["usu_login"];?></td>
            <td><a style="font-size:13px" href="ver-alta-clientes.php?cli_id=<?php echo $fila["cli_id"]?>&action=0"><?php echo($fila["cli_nombre"]);?>(<?php echo($fila["sucursal"]);?>)</td>            
            <td><?php echo(nl2br(utf8_encode($fila["ord_descripcion"])));?></td>
            <td><a href="ver-alta-proveedores.php?prv_id=<?php echo $fila["prv_id"]?>&action=0"><?php echo($fila["prv_nombre"]);?></a></td>
            <td >
                  <img src="images/estado.png" alt="estado" style="background-color:<?php echo($fila["est_color"]);?>">
                  <?php echo(utf8_encode($fila["est_nombre"]));?>
            </td>
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
</table>
    <table class="listadosALERTA" cellpadding="5">
          <tr>
            <td colspan="8" class="pie_lista"><?php 
/* PAGINADO */  ###############################################################################            
            echo(verPaginado($cant_registros, $pagina, $inicio, $final, $numPags)); 
            ?></td>
          </tr>
      </table>
    </form>
    <?php } else {   echo "<div align='center'>  <img src=images/ok.png> SIN NOVEDADES </div>";}?>
    
    
    
    
    
    
      <div class="clear"></div>
  </div>
      </div>
</body>
</html>

