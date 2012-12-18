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
        $sqlaux.=" AND ISNULL (f.fav_fecha_pago) ";

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
    
    
        $sql = "SELECT distinct f.fav_id,f.fav_fecha,c.cli_nombre,c.cli_id,c.sucursal,cc.ccc_id,f.files_id,f.fav_fecha_pago ,f.cod_factura_venta, f.gru_id 
                FROM factura_venta f,ordenes o,clientes c,grupo_ordenes g_o,cuentacorriente_cliente cc
                WHERE f.gru_id = g_o.gru_id
                AND g_o.gru_id = o.gru_id
                AND o.cli_id = c.cli_id
                AND c.cli_id = cc.cli_id
                AND f.estado = 1";
                $sql.=$sqlaux;
                $sql.=" GROUP BY  f.fav_id ";
                $sql .= " ORDER BY $unOrdenCompleta";  
           
        $resultado = mysql_query($sql);
        $cantidad = mysql_num_rows($resultado);
        $unaFila=mysql_fetch_array($resultado);
        $ccte_id=$unaFila["ccc_id"]; // echo "id maestro: $cli_idMaestro   ----la cct : $ccte_id el sql: ";
        
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
  <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script src="js/jquery.uitablefilter.js" type="text/javascript"></script>
  <script>
          function transferirFiltros(pagina)
{      
	document.getElementById("filtro").action="lista-facturas.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>  
    <script language="javascript">
		$(function() { 
		  theTable = $("#dataTableFav");
		  $("#q").keyup(function() { 
			$.uiTableFilter(theTable, this.value); 
		  });
		});
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
      <h2>Panel de control - Pago de Facturas</h2>

     <div id="buscador" >     
<form id="filtro" name="filtro" action="form-generar-pago-factura.php" method="POST">
     <table width="100%" border="0">
       <tr>
         <td width="14%"><div align="right">Cliente</div></td>
         <td width="34%">
             <select name="cli_id" id="cli_id" class="campos" required onChange="habilitarCombo2('cli_id','suc_id')" >
          <option value='0'>Seleccione</option>
          
    
           <?php
          while($fila = mysql_fetch_array($resultado1)){
    ?>
           <option value="<?php echo($fila["cli_id"]); ?>"<?php if($cli_idMaestro==$fila["cli_id"]){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?> (<?php echo(utf8_encode($fila["provincia"])); ?>/<?php echo(utf8_encode($fila["sucursal"])); ?>)</option>
           <?php
          }
    ?>
         </select></td>
         <td width="52%"></td>
       </tr>
       <tr style="visibility:hidden">
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


              <!--- Datos necesarios para el Ordenamiento header PARTE 3 -->
       <input name="orden" type="hidden" id="orden" value="<?php echo $unOrden; ?>">
       <input name="contador" type="hidden" id="contador" value="<?php echo $contadorinicial ?>">
       <!--- FIN PARTE 3 -->
     </table>
     <p>&nbsp;</p>
</form>
      </div>  
      
 <? if($cli_idMaestro!=0) { ?>     
     <div id="busqueda"  >
	<h4>Buscador : <input type="text" id="q" name="q" value="" />&nbsp;&nbsp; &nbsp; 
        <input type="checkbox" value="" id="checkbox_SelectAll" onClick="CheckboxsSeleccionarTodosFacturaVentaFav(<?php echo $cantidad ?>)" >SELECCIONAR TODO</h4> 
    </div>
<form name="formVerificadorFacturas" id="formVerificadorFacturas" method="post" enctype="multipart/form-data" action="verificador-Pago-facturas.php?cant=<?php echo $cantidad; ?>&cli_id=<?php echo $cli_idMaestro; ?>&ccc_id=<?php echo $ccte_id;?>" >      
      <table  width="100%" id="dataTableFav" class="sortable">
          <tr class="titulo">
            <td width="70">Seleccionar</td>
            <td width="70"><a href="#" onClick="agregarOrderBy('fav_id')">Factura Nro</a></td>
            <td width="100"><a href="#" onClick="agregarOrderBy('fav_fecha')">Fecha de emisión</a></td>

            <td width="32" align="center">Archivo</td>
            <td width="32">&nbsp; Ver</td>
            <td width="32">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />
                </a>
            </td>
          </tr>
  <?php    $j=0;
          while($fila = mysql_fetch_array($resultado)){
              $j++;
  ?>
          <tr class="lista" bgcolor="<?php echo($colores[$i]);?>">
            <td> 
                <input name="chckCodFactura<? echo $j ?>" type="checkbox" id="chckCodFactura<? echo $j ?>" value="<?php echo($fila["fav_id"]);?>">
                
            </td>
            <td><?php echo($fila["cod_factura_venta"]);?></td>
            <td><?php echo(tfecha($fila["fav_fecha"]));?></td>
                <?php //echo(utf8_encode($fila_req["files_id"]));
                      $id = $fila["files_id"] ?>
            <td width="60" align="center"><?php if ($id!=null) echo "<a href=descargar.php?id=$id><img src=images/download.png title=Descargar /></a>";?></td>
            

            <td width="32" align="center">
                <a href="ver-alta-factura.php?fav_id=<?php echo($fila["fav_id"]); ?>" target="_blank"><img src="images/detalles.png" alt="editar" title="Ver detalle" width="32" height="32" border="none" /></a>
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
          <input type="button" name="btnConfirmarCheckboxs" id="btnConfirmarCheckboxs" style="visibility:visible" class="botones" value="Confirmar" onClick="verificarCheckboxsFavPagos(<?php echo $j; ?>);">  
</form>
<? }else { echo "<div><b>Seleccionar un Cliente</b></div>  <br>
              <a href=index-admin.php>
                    <input type=button value=Volver class=botones /></a>       
";} ?>
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
