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
        $prv_id= $_POST["comboProveedor"];
        include("funciones.php");
        include("conexion.php");
            
        $sql ="SELECT prv_id,prv_nombre,prv_direccion,prv_cuit,iva_nombre,z.zon_nombre FROM proveedores p ,iva_tipo i,zonas z 
                WHERE p.estado = 1 
                AND prv_id = $prv_id
                AND p.iva_id = i.iva_id
                and p.zon_id = z.zon_id
                ORDER BY prv_nombre";
        
        $proveedores = mysql_query($sql);       
        $fila_proveedor = mysql_fetch_array($proveedores);
        
         
       //+++++configuracion  de descripciones a imprimir en pantalla+++++ 
       $numeroDescripcion=0;
       $totalDescripcion=1;
       
       
       //$_SESSION["ord_id"] = $ord_id;
        
       $sql = "SELECT idiva,valor from IVA";
       $iva = mysql_query($sql); 
       mysql_close();
       
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
    <td width="51%" class="titulo"><span id="ocultarParaImpresion">FACTURA N° 0001- </span> xxxx-xxxx</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><span id="ocultarParaImpresion">Buenos Aires,</span> <?php echo date("d/m/Y") ?></td>
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
  <td>&nbsp;</td>
  <td colspan="3" style="background-color:#cbeef5"><label></label></td>
</tr>
<tr>
            <td width="15%" class="titulo">Señores:</td>
            <td colspan="3" style="background-color:#cbeef5"><?php echo utf8_encode($fila_proveedor["prv_nombre"]);?></td>
       </tr>
          <tr>
            <td class="titulo">Domiclio:</td>
            <td width="24%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_proveedor["prv_direccion"]);?></td>
            <td width="9%" class="titulo">Localidad:</td>
            <td width="52%" style="background-color:#cbeef5"><?php echo utf8_encode($fila_proveedor["zon_nombre"]);?></td>
       </tr>
          <tr>
            <td class="titulo">IVA:</td>
            <td style="background-color:#cbeef5"><?php echo $fila_proveedor["iva_nombre"]?></td>
            <td class="titulo">Cuit:</td>
            <td style="background-color:#cbeef5"><?php echo (verCUIT($fila_proveedor["prv_cuit"]))?></td>
          </tr>
          <form name="frmGenerarFactura" method="post" enctype="multipart/form-data" action="alta-compra.php?prv_id=<?php echo $prv_id ?>" >
          <tr>
            <td class="titulo">N° Orden:</td>
            <td style="background-color:#cbeef5"><input name="id_orden"  type="number" id="id_orden" required onChange="return autenticaOrden()">
            <span id="incorrecto" style="font-family: Verdana, Arial, Helvetica,sans-serif;font-size: 9pt;color: #CC3300;position:relative;visibility:hidden;">Incorrecto</span>
            <span id="correcto" style="font-family: Verdana, Arial, Helvetica,sans-serif;font-size: 9pt;color: green;position:relative;visibility:hidden;">Correcto</span>
            </td>
            <td class="titulo">&nbsp;</td>
            <td style="background-color:#cbeef5">&nbsp;</td>
       </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>   
       
       
   <!-- DESCRIPCION DE FACTURA  -->
   
<div class="contenido_descripcion">
  
  <table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"><input type="hidden" src="images/add.png" onClick="addRow('dataTable')"></div></td>
  </tr>
  <tr>
    <td width="82%" class="titulo"><div align="center">Descripción</div></td>
    <td width="18%" class="titulo"><div align="center">Total compra</div></td>
  </tr>
  
  <?php while($numeroDescripcion < $totalDescripcion){
      $numeroDescripcion++;
  ?>
  
  
  <tr>
    <td><label>   
        <div align="left">
          <input name="txtDescripcionItem<?php echo($numeroDescripcion);?>"  type="text" id="txtDescripcionItem<?php echo($numeroDescripcion);?>" size="110" required>
        </div>
    </label></td>
    <td><label>
      <div align="center">
        <input type="text" align="left" required name="txtTotalItem<?php echo($numeroDescripcion);?>" style="text-align:right"  id="txtTotalItem<?php echo($numeroDescripcion);?>" value="0.00" onChange="return ActualizarTotal(<?php echo($totalDescripcion);?>,2);" >
        </div>
    </label></td>
  </tr>
  <?php } ?>
  

</table>

</div><div id="footer_factura">
  
  <table width="100%" border="0">
    <tr>
      <td width="12%">&nbsp;</td>
      <td width="31%">&nbsp;</td>
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
            <select name="comboIva" id="comboIva" onChange="return actualizarIva(2)">
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
      <td><div align="right">PERCEPCIONES</div></td>
      <td><label>
        <div align="center">
            <input type="text" style="text-align:right" value="0.00"  name="txtPercepciones" id="txtPercepciones" onChange="return ActualizarTotal(<?php echo($totalDescripcion);?>,2);">
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
      <td><a href="form-seleccionar-proveedor.php?action=2"><input type="button" value="Volver" class="botones" /></a> &nbsp; &nbsp; </td>
      <td>&nbsp;</td>
      <td><input type="submit" name="btnConfirma" id="btnConfirma" value="Confirmar">
          
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</div>

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
