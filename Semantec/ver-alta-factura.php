<?php
    include("validar.php");

    /* $action = $_GET["action"]; // 0
    if($action == 0){
          $titulo = "Datos de Orden de Servicio";
          $ord_id = $_GET["ord_id"];
    }
    else if($action == 1){
          $titulo = "Se ha dado de alta la siguiente Orden de Servicio";
          $ord_id = $_SESSION["ord_id"];
    }
    else{  2
          $titulo = "Se han modificado los datos del la siguiente Orden de Servicio"; 
          $ord_id = $_SESSION["ord_id"];
    }
    
     
     */
        //$ord_id = $_GET["ord_id"];
        $fav_id = $_GET["fav_id"];
        echo $$fav_id;
        include("funciones.php");
        include("conexion.php");
        
        $sql = "SELECT c.cli_nombre,c.cli_direccion,i.iva_nombre,c.cli_cuit ,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad
                FROM ordenes o,clientes c,iva_tipo i,factura_venta f,grupo_ordenes g_o , ubicacion u,provincias p, partidos pa,localidades l
                WHERE 
                f.fav_id 	= $fav_id
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
       
        //$nro = mysql_num_rows($datos_cliente);
       
       //+++++configuracion  de descripciones a imprimir en pantalla+++++
       $numeroDescripcion=0;
       $totalDescripcion=5;
       
       
      //DIOGETE $sql = "select fav_fecha,fav_nota from factura_venta where ord_id = $ord_id";
       $sql = "select fav_fecha,fav_nota, fav_remito, fav_condicion_vta, fav_vencimiento from factura_venta where fav_id = $fav_id";
       $fecha_factura = mysql_query($sql);
       $fila_fecha_factura = mysql_fetch_array($fecha_factura);
       
       
       
     //DIEGOTE  $sql = "select * from detalle_factura_venta where fav_id = (select fav_id from factura_venta where ord_id = $ord_id)";
       $sql = "select * from detalle_factura_venta where fav_id = $fav_id";
       $descripcion_factura = mysql_query($sql);
       $descripcion_factura2 = mysql_query($sql);
       $fila_descripcion = mysql_fetch_array($descripcion_factura2);
       
       //$idiva=$fila_descripcion["iva_idiva"];
       $idiva=$fila_descripcion["idiva"];
       $sql = "select * from iva where idiva = $idiva";
       $descrio_iva = mysql_query($sql);
       $fila_iva = mysql_fetch_array($descrio_iva);
       
       mysql_close();
       //$_SESSION["ord_id"] = $ord_id;
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
    <td rowspan="3"><a href="#" id="logo2"><img src="images/semantec-logo.jpg" width="401" height="71" alt="logo" /></a></td>
    <td width="51%" class="titulo"><span id="ocultarParaImpresion">FACTURA N° 0001- </span><?php echo $fav_id ?></td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><span id="ocultarParaImpresion">Buenos Aires,</span> <?php echo (tfecha($fila_fecha_factura["fav_fecha"])); ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><span id="ocultarParaImpresion">CUIT: 30-70877618-8</span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"> <div id="ocultarParaImpresion" align="center">Dr. Aleu 3139 (1651) - 1er piso of 11 - San Andrés <br>
      Provincia de Buenos Aires</div></td>
    <td class="titulo"><span id="ocultarParaImpresion">Ing.Brutos : 902-820067 -3</span></td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><div align="center" id="ocultarParaImpresion"><strong>I.V.A Responsable inscripto</strong></div></td>
    <td bgcolor="#F0F0F0" class="titulo"><span id="ocultarParaImpresion">Inicio de actividades: 01/06/2004</span></td>
  </tr>
</table>
   </div>
   
   
   <div id="contenedor2" style="height:auto;">
	 <table width="100%" border="0" id="dataTable">
<tr>
            <td width="15%" class="titulo"><span id="ocultarParaImpresion">Señores:</span></td>
            <td colspan="3" style="background-color:#cbeef5" align="left"><?php echo utf8_encode($fila_datos_cliente["cli_nombre"]);?></td>
       </tr>
          <tr>
            <td class="titulo"><span id="ocultarParaImpresion">Domiclio:</span></td>
            <td width="24%" style="background-color:#cbeef5"  align="left"><?php echo utf8_encode($fila_datos_cliente["cli_direccion"]);?></td>
            <td width="9%" class="titulo"><span id="ocultarParaImpresion">Localidad:</span></td>
            <td width="52%" style="background-color:#cbeef5"  align="rigth"><?php echo utf8_encode($fila_datos_cliente["provincia"]);?>/<?php echo utf8_encode($fila_datos_cliente["localidad"]);?></td>
       </tr>
          <tr>
            <td class="titulo"><span id="ocultarParaImpresion">IVA:</span></td>
            <td style="background-color:#cbeef5"  align="left"><?php echo $fila_datos_cliente["iva_nombre"]?></td>
            <td class="titulo"><span id="ocultarParaImpresion">Cuit:</span></td>
            <td style="background-color:#cbeef5"  align="rigth"><?php echo (verCUIT($fila_datos_cliente["cli_cuit"]));?></td>
          </tr>
          <tr>
            <td class="titulo"><span id="ocultarParaImpresion">Condiciones de venta:</span></td>
            <td style="background-color:#cbeef5"  align="left">&<?php echo $fila_fecha_factura["fav_condicion_vta"];?></td>
            <td class="titulo"><span id="ocultarParaImpresion">Remito:</span></td>
            <td style="background-color:#cbeef5" align="rigth">
              <?php echo $fila_fecha_factura["fav_remito"];?>
            </td>
          </tr>
          <tr>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>   
       
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
    <td width="82%" class="titulo"><div id="ocultarParaImpresion" align="center">Descripción</div></td>
    <td width="18%" class="titulo"><div id="ocultarParaImpresion" align="center">Total</div></td>
  </tr>
  
  <?php //while($numeroDescripcion < $totalDescripcion){
      //$numeroDescripcion++;
      while($item = mysql_fetch_array($descripcion_factura)){
          $precio_item = $item["det_fav_precio"];
  ?>
  
  
  <tr>
    <td><label>   
        <div align="left">
          <?echo $item["det_fav_descripcion"]; ?>
        </div>
    </label></td>
    <td><label>
      <div align="center">
        <?echo $item["det_fav_precio"]; ?>
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
      <td width="12%"><span id="ocultarParaImpresion">VENCIMIENTO:</span></td>
      <td width="31%"><label>
        <div align="center">
          <?php echo mfecha($fila_fecha_factura["fav_vencimiento"]) ;?>
          </div>
      </label></td>
      <td width="39%"><div id="ocultarParaImpresion" align="right">SUBTOTAL:</div></td>
      <td width="18%"><label>
        <div align="center">
          <?php echo $subtotal ;?>
          </div>
      </label></td>
    </tr>
    <tr>
      <td rowspan="3"><span id="ocultarParaImpresion">Nota</span></td>
      <td rowspan="3"><label>
        <?php echo $fila_fecha_factura["fav_nota"]; ?>
      </label></td>
      <td><div align="right"><span id="ocultarParaImpresion">I.V.A INSCRIP&nbsp;&nbsp;&nbsp;&nbsp;</span><?php echo $fila_iva["valor"]; ?><span id="ocultarParaImpresion"> %</span></div></td>
      <td><label>
        <div align="center">
          <?php 
          $iva_total = $subtotal*0.21;
          echo $iva_total;
          ?>
          </div>
      </label></td>
    </tr>
    <tr>
      <td><div id="ocultarParaImpresion" align="right">I.V.A NO INSCRIP.........%</div></td>
      <td><label>
        <div align="center">
          0.00
          </div>
      </label></td>
    </tr>
    <tr>
      <td><div  id="ocultarParaImpresion" align="right">TOTAL</div></td>
      <td><label>
        <div align="center">
          <?php echo $iva_total + $subtotal?>
          </div>
      </label></td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td><a href="lista-facturas.php">
    <input type="button" value="Ir al Listado"s class="botones" />
                </a>  </td>
      <td><a href="javascript:window.print()">
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

