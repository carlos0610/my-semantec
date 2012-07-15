<?php
    $titulo = "Listado de Facturas.";
        include("validar.php");
        include("funciones.php");
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
                //ordenes de los headers de las tablas
        $unOrden=$_POST['orden'];
        $contador=$_POST['contador'];
        if($contador=="")
         {$contadorinicial="3";}
        else{$contadorinicial=$contador;}
        //fin
        //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $pagado=$_POST['pagado'];
        //$cli_id=$_POST['cli_id'];
        
        //filtros nuevos Cliente Sucursal
        $cli_id = $_POST['suc_id'];
        $cli_idMaestro = $_POST['cli_id'];
        if($cli_idMaestro=="")
         {$cli_idMaestro="0";}
        $sql = "SELECT cli_id, cli_nombre,sucursal 
                FROM clientes
           WHERE sucursal_id =$cli_idMaestro
           ORDER BY cli_nombre";
        $resultadoSucursales = mysql_query($sql);
        
        
        
        
        
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.=" AND fav_id like '$elementoBusqueda%' ";}
        if($pagado!="")
        {$sqlaux.=" AND $pagado (f.fav_fecha_pago) ";}

                //filtros Cliente Sucursal PARTE B
        if($cli_id!="")
            if($cli_id=="todasLasSucursales")
                {$sqlaux.=" AND c.sucursal_id = $cli_idMaestro ";}
            else
                {$sqlaux.=" AND o.cli_id = $cli_id ";}
                     //ordenamiento parte 2
        if($unOrden=="")
        {$unOrden=" f.fav_fecha ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden ASC ";
        else
            $unOrdenCompleta.=" $unOrden DESC ";
        //fin   
        
        

    $tamPag=20;
    
    
        $sql = "SELECT distinct f.fav_id,f.fav_fecha,c.cli_nombre,c.cli_id,c.sucursal,cc.ccc_id,f.files_id,f.fav_fecha_pago 
                FROM factura_venta f,ordenes o,clientes c,grupo_ordenes g_o,cuentacorriente_cliente cc
                WHERE f.gru_id = g_o.gru_id
                AND g_o.gru_id = o.gru_id
                AND o.cli_id = c.cli_id
                AND c.cli_id = cc.cli_id
                AND f.estado = 1";
                $sql.=$sqlaux;
                $sql0=$sql;
                include("paginado.php");
                
                $sql .= " ORDER BY $unOrdenCompleta LIMIT ".$limitInf.",".$tamPag;  
               
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
  <script>
          function transferirFiltros(pagina)
{      
	document.getElementById("filtro").action="lista-facturas.php?pagina="+pagina;
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
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">
      <h2>Panel de control - Listado de Facturas</h2>

     <div id="buscador" >     
<form id="filtro" name="filtro" action="lista-facturas.php" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Cliente</div></td>
         <td width="34%"><select name="cli_id" id="cli_id" class="campos" required onChange="habilitarCombo2('cli_id','suc_id')" <?php if($cli_id==""){echo ("disabled");}?>>
          <option value='0'>Seleccione</option>
          
    
           <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_idMaestro==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
         </select></td>
         <td width="52%"><input name="chckCliente" type="checkbox" id="chckCliente" onClick="habilitarFiltrosClienteSucursal('chckCliente','cli_id','suc_id')" <?php if($cli_id!=""){echo ("checked");}?>></td>
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
         <td>&nbsp; </td>
       </tr>
       <tr>
         <td><div align="right">Pagado</div></td>
         <td><select name="pagado" id="pagado" class="campos" value="si" <?php if($pagado==""){echo ("disabled");}?>>
           <option value=" NOT ISNULL " <?php if($pagado==" NOT ISNULL "){echo(" selected=\"selected\"");} ?>>Sí</option>
           <option value=" ISNULL " <?php if($pagado==" ISNULL "){echo(" selected=\"selected\"");} ?>>No</option>
         </select></td>
         <td><input name="chkEstado" type="checkbox" id="chkEstado" onClick="habilitarFiltros('chkEstado','pagado')" <?php if($pagado!=""){echo ("checked");}?>></td>
       </tr>
       <tr>
         <td><div align="right">N° Factura </div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
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
            <td width="70"><a href="#" onClick="agregarOrderBy('fav_id')">Factura Nro</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fav_fecha')">Fecha de emisión</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('cli_nombre')">Sucursal</a></td>
            <td width="32"><a href="#" onClick="agregarOrderBy('fav_fecha_pago')">Pagada</a></td> 
            <td width="32" align="center">Archivo</td>
            <td width="32"></td>
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
            <td><a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0"><?php echo(utf8_encode($fila["cli_nombre"]));?>(<?php echo(utf8_encode($fila["sucursal"]));?>)</a></td>
            <td><?php if($fila["fav_fecha_pago"]==NULL){
                        echo "No";
                            }else {
                            echo "Sí";    
                            }?>
             </td>
                <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila["files_id"] ?>
            <td width="60" align="center"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png title=Descargar /></a>";?></td>
            
            <td width="32" align="center">
            <?php if($fila["fav_fecha_pago"]==NULL){?>
            <a href="#" onClick="pagarFactura(<?php echo($fila["fav_id"]);?> ,<?php echo($fila["ccc_id"]);?>)">
            <img src="images/pagar_factura.png" title="Registrar pago de factura">
            </a>
            
            <?php }?>
   
            </td>
            <td width="32" align="center"><a href="ver-alta-factura.php?fav_id=<?php echo($fila["fav_id"]); ?>"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="#" onClick="eliminarFactura(<?php echo($fila["fav_id"]);?> )">
                <img src="images/eliminar.png" alt="eliminar" title="Eliminar Factura" width="32" height="32" border="none" /></a></td>
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
