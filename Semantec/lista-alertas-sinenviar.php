<?php
$titulo = "Alerta de ordenes sin enviar a proveedor.";
        include("validar.php");
        include("funciones.php");       
        include("conexion.php");
        
        /* SELECTS Y FILTROS */
        
        //Clientes
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
        
        //proovedores
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1 and prv_id > 1 ORDER BY prv_nombre";
        $resultado2 = mysql_query($sql);

        //filtros Cliente Sucursal PARTE A
        $cli_id = $_POST['suc_id'];
        $cli_idMaestro = $_POST['cli_id'];
        $proveedorFiltro=$_POST['prv_id'];
        
        if($cli_idMaestro=="")
         {$cli_idMaestro="0";}
        $sql = "SELECT cli_id, cli_nombre,sucursal 
                FROM clientes
           WHERE sucursal_id =$cli_idMaestro
           AND clientes.estado = 1
           ORDER BY cli_nombre,sucursal";
        $resultadoSucursales = mysql_query($sql);
        
        
        
        
  
        $sql0 =    "SELECT ord_id, ord_codigo, u.usu_login,ord_descripcion, cli_nombre,c.sucursal,c.cli_id, prv_nombre,p.prv_id, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p,usuarios u
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 1
                    AND o.usu_id = u.usu_id";
            
                       
       //filtros Cliente Sucursal PARTE B
        if($cli_id!="")
            if($cli_id=="todasLasSucursales")
                {$sqlaux.=" AND c.sucursal_id = $cli_idMaestro ";}
            else
                {$sqlaux.=" AND o.cli_id = $cli_id ";}                 
         
       if($proveedorFiltro!="")
        {$sqlaux.=" AND o.prv_id = $proveedorFiltro ";}         
                
                
                 $sql0.= $sqlaux;       
                        
        $sql0 .=    " ORDER BY o.ord_alta DESC";
        mysql_query($sql0);
        
        
        $tamPag=10;
        include("paginado.php");
        $sql = "SELECT ord_id, ord_codigo, u.usu_login,ord_descripcion, cli_nombre,c.sucursal,c.cli_id, prv_nombre,p.prv_id, est_nombre, est_color, ord_alta, ord_plazo,ord_plazo_proveedor, ord_costo, ord_venta
                    FROM ordenes o, clientes c, estados e, proveedores p,usuarios u
                    WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1
                    AND o.est_id = 1
                    AND o.usu_id = u.usu_id";
                    
                        if($cli_id!="")
                            if($cli_id=="todasLasSucursales")
                                {$sql.=" AND c.sucursal_id = $cli_idMaestro ";}
                            else
                                {$sql.=" AND o.cli_id = $cli_id ";} 
                                
                        if($proveedorFiltro!="")
                            {$sql.=" AND o.prv_id = $proveedorFiltro ";}         
                                
                        
        $sql .=    " ORDER BY o.ord_alta DESC";
        $sql .= " LIMIT ".$limitInf.",".$tamPag;
        
        $alerta_orden_sinenviar = mysql_query($sql);
        $cantidad = mysql_num_rows($alerta_orden_sinenviar);

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        
        
        
        
        
        
        
        
      
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
        <form id="filtro" name="filtro" action="lista-alertas-sinenviar.php" method="POST">
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
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
         <td><div align="right">Cliente</div></td>
         <td><select name="cli_id" id="cli_id" class="campos" required onChange="habilitarCombo2('cli_id','suc_id')" <?php if($cli_id==""){echo ("disabled");}?>>
          <option value='0'>Seleccione</option>
          
    
           <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_idMaestro==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
         </select><label>
         <input type="checkbox" name="chkCliente" id="chkCliente" onClick="habilitarFiltrosClienteSucursal('chkCliente','cli_id','suc_id')" <?php if($cli_idMaestro!="0"){echo ("checked");}?>>
         </label></td>
         </tr>
      <tr>
         <td><div align="right">Sucursal</div></td>
         <td><select name="suc_id" id="suc_id" class="campos" required <?php if($cli_id==""){echo ("disabled");}?>>
           <option value='todasLasSucursales'>Todas las Sucursales</option>
           
           
           
           <?php
          while($fila = mysql_fetch_array($resultadoSucursales)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_id==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
           
           
           
           </select></td>
       </tr>
      <tr>
         <td><div align="right">Proveedor</div></td>
         <td><select name="prv_id" id="prv_id" class="campos" <?php if($proveedorFiltro==""){echo ("disabled");}?>>
                 <option value="1">Sin asociar</option>
           <?php while($fila2 = mysql_fetch_array($resultado2)){ ?>
           <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($proveedorFiltro==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
           <?php }?>
         </select><input name="chkProovedor" type="checkbox" id="chkProovedor" value="si" onClick="habilitarFiltros('chkProovedor','prv_id')"  <?php if($proveedorFiltro!=""){echo ("checked");}?>></td>
         </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
      </form>
  </div>
  <?php  if ($cantidad>0) {?>  
    <form  name="filtro" action="lista-alertas-sinenviar.php" method="POST">
<table class="listadosALERTA" cellpadding="3" >
          <tr class="titulo">
            <td width="60">C&oacute;digo</td>
            <td width="79">Fecha alta</td>
            <td width="81">Creada por</td>
            <td width="137">Cliente</td>
            <td width="127">Descripci&oacute;n</td>
            <td width="113">Proveedor</td>
            <td width="107">Estado</td>
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
            <td><a href="form-edit-ordenes-unificado.php?ord_id=<?php echo($fila["ord_id"]); ?>"><img src="images/editar.png" alt="editar" title="Modificar orden" width="32" height="32" border="none" /></a></td>
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

