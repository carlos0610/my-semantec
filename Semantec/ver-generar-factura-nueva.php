<?php
        include("validar.php");
        $ord_id = $_GET["ord_id"];
        include("funciones.php");
        include("conexion.php");
        include("Modelo/modeloAbonosDetalle.php");
        $sql = "SELECT  cli_id, cli_nombre FROM clientes WHERE estado=1 and sucursal_id is null ORDER BY cli_nombre";
        $resultado1 = mysql_query($sql);
       
        //$nro = mysql_num_rows($datos_cliente);
       
       //+++++configuracion  de descripciones a imprimir en pantalla+++++
       $numeroDescripcion=0;
       $totalDescripcion=20;
       
       
       $_SESSION["ord_id"] = $ord_id;
        
       $sql = "SELECT idiva,valor from iva";
       $iva = mysql_query($sql);
       
       $cli_id = $_GET["cli_id"];
       $sql = "SELECT c.cli_nombre, c.cli_cuit , i.iva_id , c.cli_rubro , c.cli_direccion , c.cli_direccion_fiscal ,
                      i.iva_nombre ,p.nombre as provincia,pa.nombre as partido,l.nombre as localidad,c.sucursal
        FROM clientes c , iva_tipo i ,ubicacion u,provincias p, partidos pa,localidades l
        WHERE  c.cli_id =$cli_id
        AND  c.iva_id = i.iva_id 
        AND c.ubicacion_id = u.id
        AND u.provincias_id = p.id
	AND u.partidos_id = pa.id
	AND u.localidades_id = l.id
        ";
        $result=mysql_query($sql); 
        $fila_datos_cliente = mysql_fetch_array($result); 
       
        $sql="SELECT `gru_id`,ord_id,ord_codigo,`ord_descripcion`,`prv_id`,`est_id`,pr.nombre as provincia,l.nombre as localidad,c.sucursal
              FROM ordenes o ,clientes c,ubicacion u,provincias pr,localidades l 
              WHERE  o.cli_id in (select cli_id from clientes where cli_id = $cli_id or sucursal_id = $cli_id )
              AND    o.cli_id = c.cli_id 
              AND    o.est_id  = 11 
              AND    o.estado  = 1
              AND    c.ubicacion_id = u.id
              AND    u.provincias_id = pr.id
              and    u.localidades_id = l.id
              AND    ISNULL(gru_id)
              ";
        $result_ordenes=mysql_query($sql); 
        $result_cantordenes=mysql_query($sql);
        $cantOrdenes= mysql_num_rows($result_cantordenes);
       //+++++++++++Gets de los checkbox
        $cantOrdenesChecadas=$_GET["cant"]; 
        $remito=$_GET["remito"]; 
        $condicionventa=$_GET["condicionventa"];
        $ocultar=$_GET["ocultar"];
        $cod_factura=$_GET["cod_factura"]; 
       
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
  <script src="js/jquery.uitablefilter.js" type="text/javascript"></script>
  <script type="text/javascript">
  $(function cargarFecha() {
      $('#vencimiento').datepick();
      $('#fechaalta').datepick();
  });
  </script>
  <script language="javascript">
		$(function() {
		  theTable = $("#dataTableOrdenes");
                  
		  $("#q").keyup(function() { 
                        document.getElementById('ver').style.display = 'block';
			$.uiTableFilter(theTable, this.value);
		  });
		});
  </script>
  <script type="text/javascript">
  
  function mostrar(obj, trig){
    var elDiv = document.getElementById(obj);
    var laFlecha = document.getElementById(trig);
    //alert( elDiv.style.display );

    if( elDiv.style.display == 'none' ){
      elDiv.style.display = 'block';
      //laFlecha.style.background = '#fff url(../images/arrows.png) no-repeat 3px 1px';
      laFlecha.style.backgroundPosition = '3px 1px';
    }
    else{
      elDiv.style.display = 'none';
      //laFlecha.style.background = '#fff url(../images/arrows.png) no-repeat 3px -15px';
      laFlecha.style.backgroundPosition = '3px -15px';
    }
  }
</script>
  </head>
  <body onload="cargarFecha()">
	
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
    <td rowspan="3"><a href="#" id="logo2"><img src="images/semantec.png" width="401" height="71" alt="logo" /></a></td>
    <td width="51%" class="titulo"><span id="ocultarParaImpresion">FACTURA N° 0001- </span> <input name="cod_factura" type="number" id="cod_factura" size="12" class="campos2" required value="<?php echo $cod_factura ?>"></td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><span id="ocultarParaImpresion">Buenos Aires,</span> <?php /*echo date("d/m/Y")*/  ?></td>
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
            <td width="15%" class="titulo">Señores:</td>
            <td colspan="3" style="background-color:#cbeef5">
             <select name="cli_id" id="cli_id" class="campos" required onChange="return refrescarDatosDeCliente(value);" >
                 <option value="0">Seleccione</option>
             <?php
                 while($fila = mysql_fetch_array($resultado1)){
                ?>
                    <option value="<?php echo($fila["cli_id"]); ?>"<?php if($fila["cli_id"]==$cli_id){echo(" selected=\"selected\"");} ?>><?php echo(utf8_encode($fila["cli_nombre"])); ?></option>
             <?php
                     }
                ?>
               </select>
                <label id="nombre" style="visibility:hidden"> </label>
                Fecha:<input name="fechaalta" type="text" id="fechaalta" value="<?php echo ($_GET["fechaalta"])?>" class="campos2" required>
            </td>
       </tr>
          <tr>
            <td class="titulo">Domicilio:</td>
            <td width="24%" style="background-color:#cbeef5"><label id="domicilio"><?php echo utf8_encode($fila_datos_cliente["cli_direccion"]);?></label></td>
            <td width="9%" class="titulo">Localidad:</td>
            <td width="52%" style="background-color:#cbeef5"><label id="localidad"><?php echo utf8_encode($fila_datos_cliente["provincia"]);?>/<?php echo utf8_encode($fila_datos_cliente["sucursal"]);?> </label></td>
       </tr>
          <tr>
            <td class="titulo">IVA:</td>
            <td style="background-color:#cbeef5"><label id="iva"><?php echo $fila_datos_cliente["iva_nombre"]?> </label></td>
            <td class="titulo">Cuit:</td>
            <td style="background-color:#cbeef5"><label id="cuit"><?php echo (verCUIT($fila_datos_cliente["cli_cuit"]));?> </label></td>
          </tr>
          <tr>
            <td class="titulo">Condiciones de venta:</td>
            <td style="background-color:#cbeef5">
                <input name="condicion_venta" type="text" id="condicion_venta" size="25" class="campos" required value="<?php echo $condicionventa; ?>">
            </td>
            <td class="titulo">Remito:</td>
            <td style="background-color:#cbeef5">
                <input name="txtRemito" class="campos2"     type="number" id="txtRemito" size="12" required value="<?php echo $remito ?>">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table> 
        <!-- Si el Cliente no tiene ordenes muestra  mensaje --> 
        <?php if($cantOrdenes!=0){ ?> 
           <!-- Muestro tabla de ordenes a seleccionar -->  
           <?php if($ocultar=="si"){      ?> 
               <div id="flecha" onclick="mostrar('ver', 'flecha')">  
                     <a href="#" id="botonver"><img src="images/btn_ver.png" alt="ver todo" /></a> 
               </div>
               <div id="busqueda"  >
			<h4>Buscador : <input type="text" id="q" name="q" value="" />&nbsp;&nbsp; &nbsp; 
                        <input type="checkbox" value="" id="checkbox_SelectAll" onClick="CheckboxsSeleccionarTodosFacturaVenta(<?php echo $cantOrdenes ?>)" >SELECCIONAR TODO</h4> 
		</div>
                
               <form name="formferificadorOrdenes" id="formferificadorOrdenes" method="post" enctype="multipart/form-data" action="verificador-generar-factura-nueva.php?cant=<?php echo $cantOrdenes; ?>&cli_id=<?php echo $cli_id; ?>" >
                   

                   <div id="ver" style="display:none;"> 
                   <table width="100%" border="0" id="dataTableOrdenes" class="sortable">  
                      <tr>
                             <td width="5%" class="titulo"><div align="center">Selección</div></td>
                             <td width="10%" class="titulo"><div align="center">Código</div></td>
                             <td width="18%" class="titulo"><div align="center">Descripción</div></td>
                             <td width="18%" class="titulo"><div align="center">Sucursal</div></td>
                      </tr>
                      
               <?php
               $i=0;
               while ($item = mysql_fetch_array($result_ordenes)) {
                   $i++;
               ?>
                   <tr>
                       <td>
                           <div align="center">
                               
                              <input type="checkbox" name="checkbox_ord_id<?php echo $i ?>" value="<?php echo $item["ord_id"]; ?>" />
                          </div>
                       </td>
                       <td><label>   
                               <div align="center">
                                        <a href="ver-alta-ordenes.php?ord_id=<?php echo($item["ord_id"]);?>&action=0" target="_blank"><? echo $item["ord_codigo"]; ?></a>
                               </div>
                           </label></td>
                       <td><label>
                               <div align="center">
                                         <? echo utf8_encode($item["ord_descripcion"]); ?>
                               </div>
                           </label></td>
                           <td><label>
                               <div align="center">
                                         <? echo utf8_encode($item["provincia"]); ?>/<? echo utf8_encode($item["sucursal"]); ?>
                               </div>
                           </label></td>
                   </tr>
                   <?php
               }
               ?>
               </table>  
                   </div>
              </form>
           <!-- FIN de Muestro tabla de ordenes a seleccionar --> 
            <?php }else{ ?>
           
           <!-- Muestro ORdenes seleccionas --> 
           
               Codigos de Órdenes Seleccionados :    <? echo $cantOrdenesChecadas ?>
        <table border="0">
        <?php
        $i=0;
        $totalOrdenVenta=0;
        while ($i <$cantOrdenesChecadas)
        { $i++;    $usu_nombre = $_SESSION["usu_nombre"]; 
                $unord_ID=$_POST["o$i"];  
                $sql5="SELECT `gru_id`,`ord_codigo`,`ord_descripcion`,`prv_id`,`est_id` ,ord_id ,ord_venta,es_abono,cli_id
              FROM `ordenes` 
              WHERE `ord_id` =$unord_ID
              AND    est_id  = 11 
              AND    estado  = 1
              AND    ISNULL(gru_id)
              ";
        $resultado_deOredenes=mysql_query($sql5); 
        $filaDeLasOrdenesCheckeadas=mysql_fetch_array($resultado_deOredenes);
        $totalOrdenVenta+=$filaDeLasOrdenesCheckeadas["ord_venta"];
       /* <a href=form-edit-ordenes.php?ord_id=<?php echo($filaDeLasOrdenesCheckeadas["ord_id"]);?>><?php echo($filaDeLasOrdenesCheckeadas["ord_codigo"]);?></a> */
        ?>

        <tr>
            <td>
            
                &nbsp;&nbsp; &nbsp; 
            </td>
            <td>                                
                    <a href="#" onClick="popup('ver-alta-ordenes.php?origenOtroForm=externo&ord_id=<?php echo($filaDeLasOrdenesCheckeadas["ord_id"]);?>', 'Alerta')">
                        # <?php echo $filaDeLasOrdenesCheckeadas["ord_codigo"],'<br> - - -  Valor venta materiales: ',$filaDeLasOrdenesCheckeadas["ord_venta"],'<br>';  ?>
                    
                    <?php         // le sumo los abonos si es abono
                           if($filaDeLasOrdenesCheckeadas["es_abono"]==1) 
                           {
                                   $filaAbono = mysql_fetch_array(getAbonosDetalleWithCliId($filaDeLasOrdenesCheckeadas["cli_id"]));
                                    echo ' - - - Valor venta Abono : ',$filaAbono['valor_venta'];
                                    $totalOrdenVenta+=$filaAbono['valor_venta'];
                             }else{// truco para q quede alineado
                                 echo '&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;
                                         &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;  '; }
                    ?>
                    </a>
            </td>
        </tr>            
        <?php      
        }       
        ?>   
         </table>
        <?php }    
        ?>
      <!-- Boton confirmar  -->    
      
      <?php if($ocultar=="si"){ ?> 
      
      <input type="button" name="btnConfirmarCheckboxs" id="btnConfirmarCheckboxs" style="visibility:visible" class="botones" value="Confirmar" onClick="verificarCheckboxsNuevo(<?php echo $i; ?>,<?php echo $cli_id; ?>);">  
      <?php } ?>
  <?php }else{ ?> <b>*No Posee Órdenes Pendientes a Facturar </b> <?php } ?>
      <!-- DESCRIPCION DE FACTURA  -->
 <?php if($ocultar=="no"){  // TOTAL de ORDENES?>  
<form name="frmGenerarFactura" method="post" enctype="multipart/form-data" action="alta-factura-nueva.php?items=<?php echo $totalDescripcion ?>" >
      
      <input name="fechaaltaOculto" type="hidden" id="fechaaltaOculto" >
      <input name="cod_facturaOculto" type="hidden" id="cod_facturaOculto" >
      <input name="condicion_ventaOculto" type="hidden" id="condicion_ventaOculto" >
      <input name="txtRemitoOculto" type="hidden" id="txtRemitoOculto" >
      <input type="hidden"  name="cantidadOrdenesAceptadas" id="cantidadOrdenesAceptadas" value="<?php echo $cantOrdenesChecadas; ?>">
      <?php // CARGO LAS ORDENES CHECADAS DE FORMA OCULTA
        $i=0;
        while ($i <$cantOrdenesChecadas)
        { $i++;  ?>
           <input type="hidden" name="ordenCheck<?php echo $i; ?>"  id="ordenCheck<?php echo $i; ?>" value="<?php echo ($_POST["o$i"]); ?>" >               
        <?php      
        }       
        ?>
      
      
      <div id="totalLabel">Total : $<?php echo $totalOrdenVenta; ?></div> 
      <div id="restaLabel">Resta: $<?php echo $totalOrdenVenta; ?></div> 
      
      <input type="hidden" name="totalOrdenVentatxt" id="totalOrdenVentatxt" style="visibility:visible" value="<?php echo $totalOrdenVenta ?>">
      <input type="hidden" name="totalOrdenVenta" id="totalOrdenVenta" style="visibility:visible" value="<?php echo $totalOrdenVenta ?>">
<div class="contenido_descripcion" style="visibility:none" enable="true" >
  
  <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"><input type="hidden" src="images/add.png" onClick="addRow('dataTable')"></div></td>
  </tr>
  <tr>
    <td width="82%" class="titulo"><div align="center">Descripción</div></td>
    <td width="18%" class="titulo"><div align="center">Total</div></td>
  </tr>
  
  <?php while($numeroDescripcion < $totalDescripcion){
      $numeroDescripcion++;
  ?>
  
  
  <tr>
    <td><label>   
        <div align="left">
          <input name="txtDescripcionItem<?php echo($numeroDescripcion);?>"  type="text" id="txtDescripcionItem<?php echo($numeroDescripcion);?>" size="110">
        </div>
    </label></td>
    <td><label>
      <div align="center">
        <input type="text" align="left" name="txtTotalItem<?php echo($numeroDescripcion);?>" style="text-align:right"  id="txtTotalItem<?php echo($numeroDescripcion);?>" value="0.00" onChange="return ActualizarTotal(<?php echo($totalDescripcion);?>,1);" >
        </div>
    </label></td>
  </tr>
  <?php } ?>
  

</table>

</div>
 <div id="footer_factura">
  
  <table width="100%" border="0">
    <tr>
      <td width="12%">VENCIMIENTO:  </td>
      <td width="31%"><input name="vencimiento" type="text" id="vencimiento" required></td>
      <td width="39%"><div align="right">SUBTOTAL:</div></td>
      <td width="18%"><label>
        <div align="center">
          <input type="text" name="txtSubtotal" style="text-align:right" value="0.00" id="txtSubtotal" readonly>
          </div>
      </label></td>
    </tr>
    <tr>
      <td rowspan="3">Nota</td>
      <td rowspan="3"><label>
        <textarea name="txtNota" id="txtNota" cols="45" rows="5"></textarea>
      </label></td>
      <td><div align="right">I.V.A INSCRIP
        <label>
            <select name="comboIva" id="comboIva" onChange="return actualizarIva(1)">
            <?php while ($fila_iva = mysql_fetch_array($iva)){  ?>
                <option value="<?php echo $fila_iva["idiva"]?>"><?php echo $fila_iva["valor"] ?></option>
                <?php }?>
        </select>
        </label>
        %</div></td>
      <td><label>
        <div align="center">
          <input type="text" style="text-align:right" value="0.00"  name="txtIva_Ins" id="txtIva_Ins" readonly>
          </div>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">I.V.A NO INSCRIP.........%</div></td>
      <td><label>
        <div align="center">
          <input type="text" style="text-align:right" value="0.00"  name="txtIva_No" id="txtIva_No" readonly>
          </div>
      </label></td>
    </tr>
    <tr>
      <td><div align="right">TOTAL</div></td>
      <td><label>
        <div align="center">
          <input type="text" style="text-align:right" value="0.00"  name="txtTotalFactura" id="txtTotalFactura" readonly>
          </div>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><div align="center">
        Adjuntar archivo <input type="file" class="" id="userfile" name="userfile" />
      </div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td> </td>
      <td>&nbsp;</td>
      <td>
         <input type="hidden"  name="codFactura" id="codFactura" value="<?php echo $cod_factura; ?>">
          <input type="submit" name="btnConfirma" id="btnConfirma" style="visibility:hidden" class="botones"  value="Confirmar" onClick="return PedirConfirmacionFacturaVenta('generar Factura','frmGenerarFactura')" >
          
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</div>
   <?php } ?>

   </div>
   <!--end contenedor-->


  <a href="index-admin.php"><input type="button" value="Volver" class="botones" /></a>
  </div>
   <!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main --><!-- fin main -->
   
   <!--start footer-->
   <footer>
<?php
    include("footer.php");
    mysql_close();
?>
   </footer>
   <!--fin footer-->

   </body>
</html>
