<?php
    $titulo = "Listado de Notas de Credito.";
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
        
//        $chkCanceladas=$_POST['chkCanceladas'];
//         if($chkCanceladas=="")
//             {$chkCanceladas="1";}
        
        
        
        
        //filtros nuevos Cliente Sucursal
        
        $cli_idMaestro = $_POST['cli_id'];
//        if($cli_idMaestro=="")
//         {$cli_idMaestro="0";}
        
        
        
        
        
        
        $sqlaux="";
        if($elementoBusqueda!="")
        {$sqlaux.=" AND cod_factura_venta like '$elementoBusqueda%' ";}
        

                //filtros Cliente Sucursal PARTE B
        if($cli_idMaestro!="")
           
                {$sqlaux.=" WHERE c.cli_id = $cli_idMaestro ";}
                     

//ordenamiento parte 2
        if($unOrden=="")
        {$unOrden=" nrc_fecha ";}
        if($contador%2)
            $unOrdenCompleta.=" $unOrden ASC ";
        else
            $unOrdenCompleta.=" $unOrden DESC ";
        //fin   
        
        

    $tamPag=20;
    
    
    
        $sql = "select nc.nrc_id,nc.nrc_codigo,nc.nrc_fecha,u.usu_nombre,c.cli_nombre,c.cli_id,sum(dnc.det_nrc_precio) as total from nota_credito nc 
                INNER JOIN detalle_nota_credito dnc
                ON nc.nrc_id = dnc.nrc_id
                INNER JOIN usuarios u
                ON nc.usu_id = u.usu_id
                INNER JOIN clientes c
                ON nc.cli_id = c.cli_id";
        
        $sql.=$sqlaux;
        $sql.=" GROUP by nrc_id";
        
        $sql0 = $sql;
    
                
          
                include("paginado.php");
                
                $sql .= " ORDER BY $unOrdenCompleta LIMIT ".$limitInf.",".$tamPag;  
           
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);
        
       

        $i = 0;
        $colores = array("#fff","#e8f7fa");
        $cant = count($colores);
        $iva=0.21 ;
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
	document.getElementById("filtro").action="lista-notas-credito.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>  
  </head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
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
      <h2>Panel de control - Listado de Notas de crédito</h2>

     <div id="buscador" >     
<form id="filtro" name="filtro" action="lista-notas-credito.php" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Cliente</div></td>
         <td width="34%"><select name="cli_id" id="cli_id" class="campos" required  <?php if($cli_id==""){echo ("disabled");}?>>
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
         
       </tr>
       <tr>
         <td><div align="right">N° Nota de crédito </div></td>
         <td><input type="text" name="filtrartxt" class="campos" value="<?php echo $elementoBusqueda; ?>"  style="text-align:right" ></td>
         <td><input type="submit" name="filtrar" value="Filtrar" class="botones" ></td>
       </tr>
       <tr>
           
       </tr>
              <!--- Datos necesarios para el Ordenamiento header PARTE 3 -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       <!--- FIN PARTE 3 -->
     </table>
     <p>&nbsp;</p>
</form>
      </div>  
      
      
      
      <table class="listados" cellpadding="5">
          <tr class="titulo">
            <td width="70"><a href="#" onClick="agregarOrderBy('nrc_id')">Nota de crédito nro</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('nrc_fecha')">Fecha de emisión</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('cli_nombre')">Sucursal</a></td>
            <td width="100" align="center"><a href="#" onClick="agregarOrderBy('total')">Total</a>
            <td width="32" align="center">Archivo</td>
            <td width="32">&nbsp; Ver</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php
          while($fila = mysql_fetch_array($resultado)){
             $ivaDeNC=$fila["total"]*$iva;
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td><?php echo($fila["nrc_codigo"]);?></td>
            <td><?php echo(tfecha($fila["nrc_fecha"]));?></td>
            <td><a href="ver-alta-clientes.php?cli_id=<?php echo($fila["cli_id"]);?>&action=0"><?php echo(utf8_encode($fila["cli_nombre"]));?></a></td>
            <td align="right"><?php echo number_format($fila["total"]+$ivaDeNC,2,',','.'); ?></td>
                <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila["files_id"] ?>
            <td width="60" align="center"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png title=Descargar /></a>";?></td>
            
            <td width="32" align="center">
                <a href="ver-alta-nota-credito.php?nrc_id=<?php echo($fila["nrc_id"]); ?>"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a>
            </td>  
            
            
            
            <td> 
            </td>
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

