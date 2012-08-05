<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de registro de pago y retenciones";
        include("validar.php");
        include("conexion.php");
        
        
        
        /*TIPOS DE PAGO*/
        
        $sql = "SELECT id,nombre FROM tipo_pago WHERE estado = 1";
        $tipo_pago = mysql_query($sql);
        
        /* BANCOS */
        $sql = "SELECT ban_id,ban_nombre FROM banco WHERE estado = 1 ORDER BY ban_nombre";
        $bancos     = mysql_query($sql);
        
        /* PROVINCIAS */
        $sql = "SELECT id,nombre FROM provincias ORDER BY nombre";
        $provincias = mysql_query($sql);
        
        /* IVA */
        $sql = "SELECT idiva,valor FROM iva WHERE idiva in (2,3)";
        $iva        = mysql_query($sql);
        
        /* CUENTABANCO */
        $sql = "SELECT id,nombre FROM cuentabanco";
        $cuentabanco = mysql_query($sql);
        
        

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
      /* Pago */
      $('#txtFechaTransferencia').datepick();
      $('#txtFechaEmision').datepick();   
      $('#txtFechaVto').datepick();
      /* Retenciones */
      $('#txtFecha1').datepick();   // Ganancias
      $('#txtFecha2').datepick();   // IVA
      $('#txtFecha3').datepick();   // IIBB
      $('#txtFecha4').datepick();   // SUSS
      
  });
  </script>    
  <script type="text/javascript" src="js/validador.js"></script>
  <script type="text/javascript" src="js/select_dependientes_cliente_sucursal.js"></script>
  <script>
          function transferirFiltros(pagina)
{    
	document.getElementById("filtro").action="lista-ordenes.php?pagina="+pagina;
	document.getElementById("filtro").submit();
}
  </script>
  <style type="text/css">
<!--
.Estilo1 {
	color: #FFFFFF
}
-->
  </style>
</head>
  <body>
	
  <!-- start main --><!-- start main --><!-- start main --><!-- start main --><!-- start main -->
  <div id="main">

    <!--start header-->
    <header>
    <a href="#" id="logo"><img src="images/semantec.png" width="470" height="100" alt="logo" /></a>
	  <!-- form login -->

    <div id ="login">
         <span id="mensaje_top" style="text-align:right;"><?php echo($_SESSION["usu_nombre"]); ?>
         <a href="logout.php"><img src="images/salir.png"  alt="salir" title="Salir" width="32" height="32" border="none" valign="middle" hspace="8" /></a>
         </span>
    </div>

    </header>
    <!--fin header-->


   <!--start contenedor-->
   <div id="contenedor" style="height:auto;">


      <h2>Panel de control</h2>
      
      <form action="alta-pago.php" method="post" enctype="multipart/form-data" enctype="multipart/form-data" >
      <table width="100%" cellpadding="5" class="forms">
          <tr class="titulo">
            <td colspan="5"> <?php echo($titulo)?> </td>
            <td width="63">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td width="83">Tipo de pago</td>
          <td width="88">
      <select name="comboTipoPago" class="campos2" id="comboTipoPago">
                <?php
                    while ($fila = mysql_fetch_array($tipo_pago)){
                
                ?>    
                    <option value="<?php echo $fila["id"]?>"><?php echo $fila["nombre"]?> </option>
                    <?php } ?>
                </select></td>
            <td width="172"><label></label></td>
            <td width="56">&nbsp;</td>
            <td width="104">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Nro Operacion</td>
            <td><label>
              <input name="txtNroOperacion" type="text" class="campos2" id="txtNroOperacion">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Banco</td>
            <td>
                    <select name="comboBanco" class="campos2" id="comboBanco">
                    <?php
                    while ($fila_banco = mysql_fetch_array($bancos)){
                
                ?>  
                        <option value="<?php echo $fila_banco["ban_id"]?>"><?php echo $fila_banco["ban_nombre"]?></option>
                  <?php } ?>
                                          </select>
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          
          <tr>
            <td>Sucursal</td>
            <td><label>
              <input name="txtSucursal" type="text" class="campos2" id="txtSucursal">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Cuenta</td>
            <td><label>
              <select name="comboCuenta" class="campos2" id="comboCuenta">
                  <?php
                    while ($fila_cuenta = mysql_fetch_array($cuentabanco)){
                ?>  
                  <option value="<?php echo$fila_cuenta["id"]?>"><?php echo $fila_cuenta["nombre"]?></option>
              <?php } ?>            
              </select>
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Fecha transf</td>
            <td><label>
              <input name="txtFechaTransferencia" type="text" class="campos2" id="txtFechaTransferencia">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Fecha emisión</td>
            <td><label>
              <input name="txtFechaEmision" type="text" class="campos2" id="txtFechaEmision">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Fecha vto:</td>
            <td><label>
              <input name="txtFechaVto" type="text" class="campos2" id="txtFechaVto">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Firmante</td>
            <td><label>
              <input name="txtFirmante" type="text" class="campos2" id="txtFirmante">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>CUIT firmante</td>
            <td><label>
              <input name="txtCuit" type="text" class="campos2" id="txtCuit">
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Importe</td>
            <td><input name="txtImportePago" type="text" class="campos2" id="txtImportePago"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Adjuntar archivo</td>
            <td><label>
              <input type="file" name="userfile" id="userfile">
            </label></td>
            <td>                            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          </table>
          
<div class="retenciones">
          <form action="alta-pago.php" method="post" enctype="multipart/form-data" enctype="multipart/form-data" >
          <table class="forms">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
          <tr>
            <td width="13%"></td>
            <td width="20%"></td>
          <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="chkGanancias" id="chkGanancias">
            Ganancias            </td>
            <td width="66%">&nbsp;</td>
            </tr>          
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha1" type="text" class="campos2" id="txtFecha1" style="text-align:right" size="12"  min="0" required  /></td>
            <td>Prefijo:
            <input name="txtPrefijo" type="text" class="campos2" id="txtPrefijo" style="text-align:right" size="5"  min="0"    />
            <label>Nro:
            <input name="txtNro" type="text" class="campos2" id="txtNro" size="20">
            </label></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><label>
              <input name="txtImporte" type="text" class="campos2" id="txtImporte" size="8">
            </label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="chkIva" id="chkIva">
            IVA            </td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha2" type="text" class="campos2" id="txtFecha2" style="text-align:right" size="12"  min="0" required  /></td>
            <td>Prefijo:
            <input name="txtPrefijo2" type="text" class="campos2" id="txtPrefijo2" style="text-align:right" size="5"  min="0"    />
            Nro:
            <input name="txtNro2" type="text" class="campos2" id="txtNro2" size="20"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte2" type="text" class="campos2" id="txtImporte2" size="8"></td>
            <td>%: 
              
              <select name="comboIva" class="campos2" id="comboIva">
                  <?php
                    while ($fila_iva = mysql_fetch_array($iva)){
                ?>
                  <option value="<?php echo $fila_iva["idiva"]?>"><?php echo $fila_iva["valor"]?></option>
                  <?php }?>
              </select>
            </td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="chkIIBB" id="chkIIBB">
            IIBB            </td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha3" type="text" class="campos2" id="txtFecha3" style="text-align:right" size="12"  min="0" required  /></td>
            <td>Prefijo:
              <input name="txtPrefijo3" type="text" class="campos2" id="txtPrefijo3" style="text-align:right" size="5"  min="0"    />
Nro:
<input name="txtNro3" type="text" class="campos2" id="txtNro3" size="20"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte3" type="text" class="campos2" id="txtImporte3" size="8"></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="chkSUSS" id="chkSUSS">
            SUSS            </td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha4" type="text" class="campos2" id="txtFecha4" style="text-align:right" size="12"  min="0" required  /></td>
            <td>Prefijo:
              <input name="txtPrefijo4" type="text" class="campos2" id="txtPrefijo4" style="text-align:right" size="5"  min="0"  />
Nro:
<input name="txtNro4" type="text" class="campos2" id="txtNro4" size="20"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte4" type="text" class="campos2" id="txtImporte4" size="8"></td>
            <td>Provincia:
              <select name="comboProvincias" class="campos2" id="comboProvincias">
                <?php
                    while ($fila_provincia = mysql_fetch_array($provincias)){
                ?>
                <option value="<?php echo $fila_provincia["id"]?>"><?php echo $fila_provincia["nombre"]?> </option>
                <?php } ?>
              </select></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
          <td><label></label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Agregar Orden" class="botones" style="visibility:visible" id="botonAgregar" /></td>
            <td>&nbsp; &nbsp;
              <input type="reset" value="Restablecer" class="botones" /></td>
            </tr>
          <tr>
            <td colspan="3" class="pie_lista">&nbsp;</td>
          </tr>
      </table> 
      </form> 
      </div>
 
      
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
