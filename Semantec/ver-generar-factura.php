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
        $ord_id = $_GET["ord_id"];
        include("funciones.php");
        include("conexion.php");
        
        $sql = "SELECT c.cli_nombre,c.cli_direccion,z.zon_nombre,i.iva_nombre,c.cli_cuit 
                FROM ordenes o,clientes c,zonas z,iva_tipo i
                    WHERE 
                    o.cli_id = c.cli_id
                    and o.ord_id = $ord_id
                    and c.zon_id = z.zon_id
                    and c.iva_id = i.iva_id";
        
       $cliente = mysql_query($sql); 
       $fila_datos_cliente = mysql_fetch_array($cliente); 
       mysql_close();
        //$nro = mysql_num_rows($datos_cliente);
       
       //+++++configuracion  de descripciones a imprimir en pantalla+++++
       $numeroDescripcion=0;
       $totalDescripcion=5;
        
       
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
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
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
    <td width="51%" class="titulo">FACTURA N° 0001- xxxx-xxxx</td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo">Buenos Aires , <?php echo date("d/m/Y") ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo">CUIT: 30-70877618-8</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"> <div align="center">Dr. Aleu 3139 (1651) - 1er piso of 11 - San Andrés <br>
      Provincia de Buenos Aires</div></td>
    <td class="titulo">Ing.Brutos : 902-820067 -3</td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="titulo"><div align="center"><strong>I.V.A Responsable inscripto</strong></div></td>
    <td bgcolor="#F0F0F0" class="titulo">Inicio de actividades: 01/06/2004</td>
  </tr>
</table>

   
   </div>
   <div id="contenedor2" style="height:auto;">
	 <table width="100%" border="0" id="dataTable">
<tr>
            <td width="15%" class="titulo">Señores:</td>
            <td colspan="3" style="background-color:#cbeef5"><?php echo $fila_datos_cliente["cli_nombre"]?></td>
       </tr>
          <tr>
            <td class="titulo">Domiclio:</td>
            <td width="24%" style="background-color:#cbeef5"><?php echo $fila_datos_cliente["cli_direccion"]?></td>
            <td width="9%" class="titulo">Localidad:</td>
            <td width="52%"><?php echo $fila_datos_cliente["zon_nombre"]?></td>
       </tr>
          <tr>
            <td class="titulo">IVA:</td>
            <td><?php echo $fila_datos_cliente["iva_nombre"]?></td>
            <td class="titulo">Cuit:</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="titulo">Condiciones de venta:</td>
            <td>&nbsp;</td>
            <td class="titulo">Remito:</td>
            <td>
              <input name="txtRemito" type="text" id="txtRemito" size="12">
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
     </table>   
       
       
   <!-- DESCRIPCION DE FACTURA  -->
   
	 <div class="contenido_descripcion"><form><table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"><input type="hidden" src="images/add.png" onClick="addRow('dataTable')"><img src="images/eliminar.png" width="32" height="32"></div></td>
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
          <input name="txtDescripcionItem<?php echo($numeroDescripcion);?>" type="text" id="txtDescripcionItem<?php echo($numeroDescripcion);?>" size="110">
        </div>
    </label></td>
    <td><label>
      <div align="center">
        <input type="text" align="left" name="txtTotalItem<?php echo($numeroDescripcion);?>" id="txtTotalItem<?php echo($numeroDescripcion);?>" value="0.00" onChange="return ActualizarTotal(<?php echo($totalDescripcion);?>);" >
        </div>
    </label></td>
  </tr>
  <?php } ?>
  

</table>
	 </form>
</div><div id="footer_factura.php">
  <table width="100%" border="0">
    <tr>
      <td width="12%">VENCIMIENTO: </td>
      <td width="31%">&nbsp;</td>
      <td width="38%"><div align="right">SUBTOTAL:</div></td>
      <td width="19%"><label>
        <input type="text" name="txtSubtotal" id="txtSubtotal">
      </label></td>
    </tr>
    <tr>
      <td rowspan="3">Nota</td>
      <td rowspan="3"><label>
        <textarea name="txtArea" id="txtArea" cols="45" rows="5"></textarea>
      </label></td>
      <td><div align="right">I.V.A INSCRIP.........%</div></td>
      <td><label>
        <input type="text" name="txtIva_Ins" id="txtIva_Ins">
      </label></td>
    </tr>
    <tr>
      <td><div align="right">I.V.A NO INSCRIP.........%</div></td>
      <td><label>
        <input type="text" name="txtIva_No" id="txtIva_No">
      </label></td>
    </tr>
    <tr>
      <td><div align="right">TOTAL</div></td>
      <td><label>
        <input type="text" name="txtTotalFactura" id="txtTotalFactura">
      </label></td>
    </tr>
  </table>
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