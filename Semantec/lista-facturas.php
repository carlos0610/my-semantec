<?php
    $titulo = "Listado de Facturas.";
        include("validar.php");
        include("funciones.php");
        include("conexion.php");
        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1";
        $resultado1 = mysql_query($sql);
        //recibo los criterios y construyo la consulta
        $elementoBusqueda=$_POST['filtrartxt'];
        $pagado=$_POST['pagado'];
        $cli_id=$_POST['cli_id'];
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.=" AND fav_id like '$elementoBusqueda%' ";}
        if($pagado!="")
        {$sqlaux.=" AND $pagado (f.fav_fecha_pago) ";}
        if($cli_id!="")
        {$sqlaux.=" AND c.cli_id  = $cli_id ";}
        
        
        
/* CALCULO PAGINADO */  ###############################################################################
 /*   $sql0 =    "SELECT distinct f.fav_id,f.fav_fecha,c.cli_nombre,cc.ccc_id,f.files_id,f.fav_fecha_pago 
                FROM factura_venta f,ordenes o,clientes c,grupo_ordenes g_o,cuentacorriente_cliente cc
                WHERE f.gru_id = g_o.gru_id
                AND g_o.gru_id = o.gru_id
                AND o.cli_id = c.cli_id
                AND c.cli_id = cc.cli_id
                AND f.estado = 1
                ORDER BY f.fav_fecha desc"; */
    $tamPag=10;
    
    
        $sql = "SELECT distinct f.fav_id,f.fav_fecha,c.cli_nombre,cc.ccc_id,f.files_id,f.fav_fecha_pago 
                FROM factura_venta f,ordenes o,clientes c,grupo_ordenes g_o,cuentacorriente_cliente cc
                WHERE f.gru_id = g_o.gru_id
                AND g_o.gru_id = o.gru_id
                AND o.cli_id = c.cli_id
                AND c.cli_id = cc.cli_id
                AND f.estado = 1";
                $sql.=$sqlaux;
                $sql0=$sql;
                include("paginado.php");
                
                $sql .= " ORDER BY f.fav_fecha desc LIMIT ".$limitInf.",".$tamPag;                      
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
<form name="filtro" action="<?php echo $PHP_SELF;?>" method="POST">
     Cliente  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select name="cli_id" id="cli_id" class="campos" <?php if($cli_id==""){echo ("disabled");}?>>
    <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_id==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?></option>
    <?php
          }
    ?>
                </select>
     <input name="chckCliente" type="checkbox" id="chckCliente" onClick="habilitarFiltros('chckCliente','cli_id')" <?php if($cli_id!=""){echo ("checked");}?>><br>
     Pagado &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select name="pagado" id="pagado" class="campos" value="si" <?php if($pagado==""){echo ("disabled");}?>>
            <option value=" NOT ISNULL " <?php if($pagado==" NOT ISNULL "){echo(" selected=\"selected\"");} ?>>Sí</option> 
            <option value=" ISNULL " <?php if($pagado==" ISNULL "){echo(" selected=\"selected\"");} ?>>No</option>
                </select>
      <input name="chkEstado" type="checkbox" id="chkEstado" onClick="habilitarFiltros('chkEstado','pagado')" <?php if($pagado!=""){echo ("checked");}?>><br>
     N° Factura &nbsp;
<input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" >
<input type="submit" name="filtrar" value="Filtrar" class="botones" >
</form>
      </div>  
      
      
      
      <table class="sortable" cellpadding="5">
          <tr class="titulo">
            <td width="70">Factura Nro</td>
            <td width="100">Fecha de emisión</td>
            <td width="100">Cliente</td>
            <td width="32">Pagada</td> 
            <td width="32">Archivo</td>
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
            <td><?php echo(utf8_encode($fila["cli_nombre"]));?></td>
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
            <a href="#" onclick="pagarFactura(<?php echo($fila["fav_id"]);?> ,<?php echo($fila["ccc_id"]);?>)">
            <img src="images/pagar_factura.png" title="Registrar pago de factura">
            </a>
            
            <?php }?>
   
            </td>
            <td width="32" align="center"><a href="ver-alta-factura.php?fav_id=<?php echo($fila["fav_id"]); ?>"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a></td>            
            <td><a href="#" onclick="eliminarFactura(<?php echo($fila["fav_id"]);?> )">
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
