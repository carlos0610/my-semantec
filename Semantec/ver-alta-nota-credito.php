<?php
    include("validar.php");
        $origen = $_GET["origen"]; 
        $nrc_id = $_GET["nrc_id"];
        include("funciones.php");
        include("conexion.php");
        
        $sql = "SELECT c.cli_nombre,c.cli_direccion,i.iva_nombre,c.cli_cuit ,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,  c.cli_direccion_fiscal,c.sucursal_id
                FROM ordenes o,clientes c,iva_tipo i,factura_venta f,grupo_ordenes g_o , ubicacion u,provincias p, partidos pa,localidades l
                WHERE 
                f.fav_id in (select fv.fav_id from nota_credito nc
										INNER JOIN grupo_fav_nota gfv
										ON nc.gfn_id = gfv.gfn_id
										INNER JOIN factura_venta fv
										ON fv.grupo_nota_credito = gfv.gfn_id
										WHERE nc.nrc_id = $nrc_id)
                and f.gru_id    = g_o.gru_id
                and g_o.gru_id = o.gru_id
                and o.cli_id = c.cli_id
                AND c.ubicacion_id = u.id
                AND u.provincias_id = p.id
                AND u.partidos_id = pa.id
                AND u.localidades_id = l.id
                and c.iva_id = i.iva_id";
        
       $cliente = mysql_query($sql); 
       $fila_datos_cliente = mysql_fetch_array($cliente); 
       
              //Busco Sucursal Matriz
       $SucursalMatriz=$fila_datos_cliente['sucursal_id'];
       $SucursalMatrizLocalidad=$fila_datos_cliente["provincia"];
       if($SucursalMatriz!=null)
       {
           $sql = "SELECT  c.cli_id , c.sucursal_id, c.ubicacion_id, c.cli_nombre,c.cli_cuit, c.iva_id, c.cli_rubro, c.cli_direccion, c.cli_direccion_fiscal, c.cli_telefono, c.sucursal,
                            p.nombre as provincia,pa.nombre as partido,l.nombre as localidad
                   FROM  clientes c , ubicacion u,provincias p, partidos pa,localidades l
                   WHERE c.cli_id  =$SucursalMatriz
                   AND c.ubicacion_id = u.id
                   AND u.provincias_id = p.id
                   AND u.partidos_id = pa.id
                   AND u.localidades_id = l.id
                    ";
           $sucursal = mysql_query($sql); 
           $fila_sucursal_Matriz = mysql_fetch_array($sucursal); 
           $SucursalMatrizLocalidad=$fila_sucursal_Matriz['provincia']; 
       }
       //+++++configuracion  de descripciones a imprimir en pantalla+++++
       $numeroDescripcion=0;
       $totalDescripcion=5;
       
       
      //DIOGETE $sql = "select fav_fecha,fav_nota from factura_venta where ord_id = $ord_id";
       $sql = "select nrc_codigo,idiva,nrc_fecha,gfn_id,nrc_nota from nota_credito
                WHERE nrc_id  = $nrc_id";
       $fecha_factura = mysql_query($sql);
       $fila_fecha_factura = mysql_fetch_array($fecha_factura);
      //busca Codigo de Ordenes asociadas a esta factura dado un  gru_id , transformar en funcion
       $grupo_id=$fila_fecha_factura["gfn_id"]; 
       $sql = "select fav_id,cod_factura_venta from factura_venta where grupo_nota_credito = $grupo_id";  
       $codigo_de_ordenesFav = mysql_query($sql);
       
      
       $sql = "select * from detalle_nota_credito where nrc_id = $nrc_id";
       $descripcion_factura = mysql_query($sql);
       $descripcion_factura2 = mysql_query($sql);
       $fila_descripcion = mysql_fetch_array($descripcion_factura2);
       
       
       $idiva=$fila_descripcion["idiva"];
       $sql = "select * from iva where idiva = $idiva";
       $descrio_iva = mysql_query($sql);
       $fila_iva = mysql_fetch_array($descrio_iva);
       
       mysql_close();
       
       $subtotal = 0;
       
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
  <script type="text/javascript">
  $(function() {
      $('#ord_plazo').datepick();
  });
  </script></head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header><!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;"><?php echo(utf8_encode($_SESSION["usu_nombre"])); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenido1" style="height:auto">
   <table width="100%" border="0">
  <tr>
    <td rowspan="3" width="60%"><a href="#" id="logo2"><img src="images/semantec-logo.jpg" width="401" height="71" alt="logo" /></a></td>
    <td width="51%" class="titulo_nota_credito"><span id="ocultarParaImpresion">Nota de crédito N° </span><?php echo ($fila_fecha_factura["nrc_codigo"]); ?></td>
    <td width="1%">&nbsp;</td>
  </tr>
    <tr>
    <td class="titulo_nota_credito"></td>
    <td>&nbsp;</td>
  </tr>
  
  
  
  
  <tr>
    <td class="titulo_nota_credito"><span id="ocultarParaImpresion">Buenos Aires,</span> <?php echo (tfecha($fila_fecha_factura["nrc_fecha"])); ?></td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td class="titulo_nota_credito"> <div id="ocultarParaImpresion" align="center">Dr. Aleu 3139 (1651) - 1er piso of 11 - San Andrés <br>
      Provincia de Buenos Aires</div></td>
    <td class="titulo_nota_credito"><span id="ocultarParaImpresion">Ing.Brutos : 902-820067 -3</span></td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo_nota_credito"><div align="center" id="ocultarParaImpresion"><strong>I.V.A Responsable inscripto</strong></div></td>
    <td bgcolor="#F0F0F0" class="titulo_nota_credito"><span id="ocultarParaImpresion">Inicio de actividades: 01/06/2004</span></td>
  </tr>
</table>
   </div>
   
   
   <div id="contenedor2" style="height:auto;">
	 <table width="100%" border="0" id="dataTable">
<tr>
            <td width="15%" class="titulo_nota_credito">Señores:</td>
            <td style="background-color:#CCCCFF">
            <?php echo utf8_encode($fila_datos_cliente["cli_nombre"]);?> 
                <label id="nombre" style="visibility:hidden"> </label>
            </td>
            
            
       </tr>
          <tr>
            <td class="titulo_nota_credito" >Domicilio:</td>
            <td width="24%" style="background-color:#CCCCFF"><label id="domicilio"><?php echo utf8_encode($fila_datos_cliente["cli_direccion_fiscal"]);?></label></td>
            <td width="9%" class="titulo_nota_credito">Localidad:</td>
            <td width="52%" style="background-color:#CCCCFF"><label id="localidad"><?php echo utf8_encode($SucursalMatrizLocalidad);?> </label></td>
       </tr>
          <tr>
            <td class="titulo_nota_credito">IVA:</td>
            <td style="background-color:#CCCCFF"><label id="iva"><?php echo $fila_datos_cliente["iva_nombre"]?> </label></td>
            <td class="titulo_nota_credito">Cuit:</td>
            <td style="background-color:#CCCCFF"><label id="cuit"><?php echo (verCUIT($fila_datos_cliente["cli_cuit"]));?> </label></td>
          </tr>
          <tr>            
          </tr>
          <tr>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>   
       
   </div>
   <!-- IMPRESION DE CODIGO DE ORDENES ASOCIADAS  -->
   <div  style="height:auto;">
       Facturas de venta asociadas :
     <?php 
      while($cod = mysql_fetch_array($codigo_de_ordenesFav)){?>
       <a href="ver-alta-factura.php?fav_id=<?php   echo $cod["fav_id"]; ?>&origenOtroForm=externo"> <?php   echo ' N° ',$cod["cod_factura_venta"],'  '; ?> </a>&nbsp;&nbsp;
    <?php   }
  ?>
    </div>
   <!-- DESCRIPCION DE FACTURA  -->
   
<div class="contenido_descripcion">
  <form name="frmGenerarFactura" method="post" enctype="multipart/form-data" action="alta-factura.php?ord_id=<?php echo $ord_id ?>&items=<?php echo $totalDescripcion?>">
  <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
  <tr>
   
    <td width="82%" class="titulo_nota_credito"><div id="ocultarParaImpresion" align="center">Descripción</div></td>
    <td width="18%" class="titulo_nota_credito"><div id="ocultarParaImpresion" align="center">Total</div></td>
  </tr>
  
  <?php //while($numeroDescripcion < $totalDescripcion){
      //$numeroDescripcion++;
      while($item = mysql_fetch_array($descripcion_factura)){
          $precio_item = $item["det_nrc_precio"];
  ?>
  
  
  <tr>
    
    <td><label>   
        <div align="left">
          <?echo $item["det_nrc_descripcion"]; ?>
        </div>
    </label></td>
    <td><label>
      <div align="right">
        <? if($item["det_nrc_precio"]>0)
        {
            echo "$",number_format($item["det_nrc_precio"], 2, ',', '.');
        }
        else
             echo "S/C";
?>
        </div>
    </label></td>
  </tr>
  <?php 
  
  $subtotal += $precio_item;
  } ?>
  

</table>
</div>
   <br>
<div id="footer_factura">
  
  <table width="100%" border="0">
    <tr>
      <td rowspan="3"><span id="ocultarParaImpresion">Nota</span></td>
      <td rowspan="3"><label>
        <?php echo $fila_fecha_factura["nrc_nota"]; ?>
      </label></td>
      <td height="31"><div align="right"><span id="ocultarParaImpresion">I.V.A INSCRIP&nbsp;&nbsp;&nbsp;&nbsp;</span><?php echo number_format($fila_iva["valor"], 2, ',', '.'); ?><span id="ocultarParaImpresion"> %</span></div></td>
      <td><label>
        <div align="right">
          $&nbsp;<?php 
          $iva_total = $subtotal*0.21;
          echo number_format($iva_total, 2, ',', '.');
          ?>
          </div>
      </label></td>
    </tr>
    
    
    <tr>
      <td><div  id="ocultarParaImpresion" align="right">SUBTOTAL</div></td>
      <td><label>
        <div align="right">
          $<?php echo number_format( $subtotal, 2, ',', '.')?>
          </div>
      </label></td>
    </tr>
    <tr>
      <td><div  id="ocultarParaImpresion" align="right">TOTAL</div></td>
      <td><label>
        <div align="right">
          $<?php echo number_format(($iva_total + $subtotal), 2, ',', '.')?>
          </div>
      </label></td>
    </tr>
    
    <tr>
      <td></td>
      <td>
          <?php if ($origen!='externo')  {?>
                <a href="lista-notas-credito.php">
                     <input type="button" value="Ir al Listado"s class="botones" />
                </a>  
                <a href="form-generar-nota-credito.php">
                     <input type="button" value="Nueva NC"s class="botones" />
                </a> 
          <?php }else { ?>
                 <input type="button" class="botones" value="Volver" onclick="goBack()" />
          <?php } ?>
      </td>
      <td><a href="ver-alta-nota-credito-pdf.php?nrc_id=<?php echo$nrc_id; ?> "target="_blank">
              <img id="logoImpresora" src="images/imprimir.png" heigth="48" width="48"/></a>
          
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</div>

   </div>
   <!--end contenedor-->
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


