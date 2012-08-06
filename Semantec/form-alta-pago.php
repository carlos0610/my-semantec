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
      <table width="100%" cellpadding="5" class="listados">
          <tr class="titulo">
            <td colspan="5"> <?php echo($titulo)?> </td>
            <td width="66">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td width="120">Tipo de pago</td>
          <td width="146">
      <select name="comboTipoPago1" class="campos2" id="comboTipoPago1" onClick="filtroTipoDePago(value,1)">
          <option value="0">Seleccione </option>
                <?php
                    while ($fila = mysql_fetch_array($tipo_pago)){
                
                ?>    
                    <option value="<?php echo $fila["id"]?>"><?php echo $fila["nombre"]?> </option>
                    <?php } ?>
                </select></td>
            <td width="194"><label></label></td>
            <td width="238">&nbsp;</td>
            <td width="44">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Nro Operación</td>
            <td><label>
              <input name="txtNroOperacion1" type="text" class="campos2" id="txtNroOperacion1" >
            </label></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Banco</td>
            <td>
                    <select name="comboBanco" class="campos2" id="comboBanco1" disabled>
                    <?php
                    while ($fila_banco = mysql_fetch_array($bancos)){
                
                ?>  
                        <option value="<?php echo $fila_banco["ban_id"]?>"><?php echo $fila_banco["ban_nombre"]?></option>
                  <?php } ?>
                                          </select>
            </label></td>
            <td>Sucursal</td>
            <td><input name="txtSucursal" type="text" class="campos2" id="txtSucursal1" disabled></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          
          <tr>
            <td>Fecha emisión</td>
            <td><input name="txtFechaEmision" type="text" class="campos2" id="txtFechaEmision1" disabled></td>
            <td>Fecha vto:</td>
            <td><input name="txtFechaVto" type="text" class="campos2" id="txtFechaVto" disabled></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Firmante</td>
            <td><input name="txtFirmante" type="text" class="campos2" id="txtFirmante" disabled></td>
            <td>CUIT firmante</td>
            <td><input name="txtCuit" type="text" class="campos2" id="txtCuit" disabled></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Cuenta</td>
            <td><select name="comboCuenta" class="campos2" id="comboCuenta" disabled>
              <?php
                    while ($fila_cuenta = mysql_fetch_array($cuentabanco)){
                ?>
              <option value="<?php echo$fila_cuenta["id"]?>"><?php echo $fila_cuenta["nombre"]?></option>
              <?php } ?>
            </select></td>
            <td>Fecha transf</td>
            <td><input name="txtFechaTransferencia" type="text" class="campos2" id="txtFechaTransferencia" disabled></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td>Importe</td>
            <td><input name="txtImportePago" type="text" class="campos2" id="txtImportePago"></td>
            <td>Adjuntar archivo</td>
            <td><input type="file" name="userfile" id="userfile"></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          </table>
          
<div class="retenciones">
          <form action="alta-pago.php" method="post" enctype="multipart/form-data" enctype="multipart/form-data" >
          <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
          <tr>
            <td width="7%"></td>
            <td width="18%"></td>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkGanancias" id="chkGanancias" onClick="habilitarRetenciones('chkGanancias',1)">
            Ganancias            </td>
            <td width="75%" bgcolor="#CDDCDA">&nbsp;</td>
            </tr>          
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha1" type="text" class="campos2" id="txtFecha1" style="text-align:right" size="12"  min="0" required disabled  /></td>
            <td>Prefijo :
              <input name="txtPrefijo1" type="text" class="campos2" id="txtPrefijo1" style="text-align:right" size="5"  min="0"  disabled  />
            <label>Nro :
              <input name="txtNro1" type="text" class="campos2" id="txtNro1" size="20" disabled>
            </label></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><label>
              <input name="txtImporte1" type="text" class="campos2" id="txtImporte1" size="8" disabled>
            </label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIva" id="chkIva" onClick="habilitarRetenciones('chkIva',2)">
            IVA            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha2" type="text" class="campos2" id="txtFecha2" style="text-align:right" size="12"  min="0" required disabled /></td>
            <td>Prefijo :
              
              <input name="txtPrefijo2" type="text" class="campos2" id="txtPrefijo2" style="text-align:right" size="5"  min="0" disabled   />
              Nro :
<input name="txtNro2" type="text" class="campos2" id="txtNro2" size="20" disabled></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte2" type="text" class="campos2" id="txtImporte2" size="8" disabled></td>
            <td>%: 
              
              <select name="comboIva" class="campos2" id="comboIva" disabled>
                  <?php
                    while ($fila_iva = mysql_fetch_array($iva)){
                ?>
                  <option value="<?php echo $fila_iva["idiva"]?>"><?php echo $fila_iva["valor"]?></option>
                  <?php }?>
              </select>
            </td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIIBB" id="chkIIBB" onClick="habilitarRetenciones('chkIIBB',3)">
            IIBB            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha3" type="text" class="campos2" id="txtFecha3" style="text-align:right" size="12"  min="0" required disabled /></td>
            <td>Prefijo :
              <input name="txtPrefijo3" type="text" class="campos2" id="txtPrefijo3" style="text-align:right" size="5"  min="0" disabled   />
Nro :
<input name="txtNro3" type="text" class="campos2" id="txtNro3" size="20" disabled></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte3" type="text" class="campos2" id="txtImporte3" size="8" disabled></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkSUSS" id="chkSUSS" onClick="habilitarRetenciones('chkSUSS',4)">
            SUSS            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha4" type="text" class="campos2" id="txtFecha4" style="text-align:right" size="12"  min="0" required disabled /></td>
            <td>Prefijo :
              <input name="txtPrefijo4" type="text" class="campos2" id="txtPrefijo4" style="text-align:right" size="5"  min="0" disabled />
Nro :
<input name="txtNro4" type="text" class="campos2" id="txtNro4" size="20" disabled ></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte4" type="text" class="campos2" id="txtImporte4" size="8" disabled></td>
            <td>Provincia:
              <select name="comboProvincias" class="campos2" id="comboProvincias" disabled>
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
            <td>&nbsp;</td>
            <td>&nbsp; <input type="submit" value="Agregar Orden" class="botones" style="visibility:visible" id="botonAgregar" />
              &nbsp;
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