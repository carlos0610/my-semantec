<?php
    $titulo = "Listado de Ordenes de Servicio.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
         $tamPag=10;
         
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
         
         
         // estados
        $sql = "SELECT  est_id, est_nombre, est_color FROM estados";
        $resultado3 = mysql_query($sql);
        //proovedores
        $sql = "SELECT  prv_id, prv_nombre FROM proveedores WHERE estado=1";
        $resultado2 = mysql_query($sql);
        //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $proveedorFiltro=$_POST['prv_id'];
        $estado_id=$_POST['est_id'];
        //filtros Cliente Sucursal PARTE A
        $cli_id = $_POST['suc_id'];
        $cli_idMaestro = $_POST['cli_id'];
        if($cli_idMaestro=="")
         {$cli_idMaestro="0";}
        $sql = "SELECT cli_id, cli_nombre,sucursal 
                FROM clientes
           WHERE sucursal_id =$cli_idMaestro
           AND clientes.estado = 1
           ORDER BY cli_nombre,sucursal";
        $resultadoSucursales = mysql_query($sql);
    
        //ordenes de los headers de las tablas parte 1
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.="AND ord_codigo like '$elementoBusqueda%' ";}
        if($proveedorFiltro!="")
        {$sqlaux.="AND o.prv_id = $proveedorFiltro ";}
        if($estado_id!="")
        {$sqlaux.="AND o.est_id = $estado_id ";}
        //filtros Cliente Sucursal PARTE B
        if($cli_id!="")
            if($cli_id=="todasLasSucursales")
                {$sqlaux.=" AND c.sucursal_id = $cli_idMaestro ";}
            else
                {$sqlaux.=" AND o.cli_id = $cli_id ";}
        
        
        //ordenamiento parte 2
        if($unOrden=="")
             {$unOrden=" o.ord_alta ";}
        if($unOrden=="ord_codigo") // valores para ordenar de forma numerica expresada en varchar 
             {$unOrdenCompleta=" ABS ";}
        if($contador%2)
            $unOrdenCompleta.=" ( $unOrden ) ASC ";
        else
            $unOrdenCompleta.=" ( $unOrden ) DESC ";
        //fin

$sql="SELECT ord_id, ord_codigo, ord_descripcion, cli_nombre,c.cli_id, prv_nombre,o.prv_id, est_nombre, est_color, ord_alta, ord_plazo, ord_costo, ord_venta,c.sucursal
                  FROM ordenes o, clientes c, estados e, proveedores p
                  WHERE o.cli_id = c.cli_id
                    AND o.est_id = e.est_id
                    AND o.prv_id = p.prv_id  
                    AND o.estado = 1 ";
                    $sql.=$sqlaux;
               //     AND ord_codigo like '$elementoBusqueda%' 
               //     AND o.est_id = $estado_id
               //     AND o.prv_id = $proveedorFiltro
               //     ORDER BY o.ord_alta DESC ";
                    $sql0=$sql;
                    include("paginado.php");
                    $sql .= " ORDER BY  $unOrdenCompleta   LIMIT ".$limitInf.",".$tamPag;  

                    
                    $resultado=mysql_query($sql);
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
      <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-ordenes.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>
  <script type="text/javascript" src="js/select_dependientes_cliente_sucursal.js"></script>
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
      <h2>Panel de control - Listado de Ordenes de Servicio</h2>

      
      <div id="buscador" >     
    <form id="filtro" name="filtro" action="lista-ordenes.php<?php /*echo $PHP_SELF; */?>" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="15%">&nbsp;</td>
         <td width="34%">&nbsp;</td>
         <td width="51%">&nbsp;</td>
       </tr>
       <tr>
         <td><div align="right">Proveedor</div></td>
         <td><select name="prv_id" id="prv_id" class="campos" <?php if($proveedorFiltro==""){echo ("disabled");}?>>
           <?php while($fila2 = mysql_fetch_array($resultado2)){ ?>
           <option value="<?php echo($fila2["prv_id"]); ?>"<?php if($proveedorFiltro==$fila2["prv_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila2["prv_nombre"])); ?></option>
           <?php }?>
         </select></td>
         <td><input name="chkProovedor" type="checkbox" id="chkProovedor" value="si" onClick="habilitarFiltros('chkProovedor','prv_id')"  <?php if($proveedorFiltro!=""){echo ("checked");}?>></td>
       </tr>
       <tr>
         <td><div align="right">Estado</div></td>
         <td><select name="est_id" id="est_id" class="campos" value="si" <?php if($estado_id==""){echo ("disabled");}?>>
           <?php  while($fila3 = mysql_fetch_array($resultado3)){  ?>
           <option style="background-color:<?php echo($fila3["est_color"]); ?>" value="<?php echo($fila3["est_id"]); ?>"<?php if($estado_id==$fila3["est_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila3["est_nombre"])); ?></option>
           <?php    } ?>
         </select></td>
         <td><input name="chkEstado" type="checkbox" id="chkEstado" onClick="habilitarFiltros('chkEstado','est_id')" <?php if($estado_id!=""){echo ("checked");}?>></td>
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
         </select></td>
         <td><label>
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
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td><div align="right">N° Orden</div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
       </tr> 
       
       <!--- Datos necesarios para el header PARTE 3 -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       <!--- FIN PARTE 3 -->
     </table>
     <p>&nbsp;</p>
     </form>
      </div>    
   
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="70"><a href="#" onClick="agregarOrderBy('ord_codigo')">C&oacute;digo</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('ord_alta')">Fecha</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('cli_nombre')">Cliente</a></td>
            <td>Descripci&oacute;n</td>
            <td width="100"><a href="#" onClick="agregarOrderBy('prv_nombre')">Proveedor</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('est_nombre')">Estado</a></td>
            <td width="32">&nbsp;</td>            
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
            <td><?php echo($fila["ord_codigo"]);?></td>
            <td><?php echo(tfecha($fila["ord_alta"]));?></td>
            <td><a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0"><?php echo(utf8_encode($fila["cli_nombre"]));?>(<?php echo(utf8_encode($fila["sucursal"]))?>)</a></td>
            <td><?php echo(nl2br(utf8_encode($fila["ord_descripcion"])));?></td>
            <td><a href="ver-alta-proveedores.php?prv_id=<?php echo $fila["prv_id"]?>&action=0"><?php echo(utf8_encode($fila["prv_nombre"]));?></a></td>
            <td>
                  <img src="images/estado.png" alt="estado" style="background-color:<?php echo($fila["est_color"]);?>">
                  <?php echo(utf8_encode($fila["est_nombre"]));?>
            </td>
            <td width="32"><a href="ver-alta-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=0"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="form-edit-ordenes.php?ord_id=<?php echo($fila["ord_id"]); ?>&action=0"><img src="images/editar.png" alt="editar" title="Modificar orden" width="32" height="32" border="none" /></a></td>
            <td><a href="#" onClick="eliminarOrden(<?php echo($fila["ord_id"]);?>,'<?php echo($fila["ord_codigo"]);?>')">
                <img src="images/eliminar.png" alt="eliminar" title="Eliminar orden" width="32" height="32" border="none" /></a></td>
          </tr>
  <?php
            $i++;
            if($i==$cant){$i=0;}

          }
  ?>
      </table>
      <table  class="listados" cellpadding="5">
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
