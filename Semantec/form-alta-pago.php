<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de registro de pago y retenciones";
        include("validar.php");
        include("conexion.php");
        $fav_id     =  $_GET["fav_id"]; 
        $ccc_id     =  $_GET["ccc_id"];
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
        /* Cantidad de Tipo Pago */
        
        $cantTipoPago=1;
        $cantTipoPagogene  =  $_POST["cantidadTip"]; 
        if($cantTipoPagogene>0)
            {
                  $cantTipoPago=$cantTipoPagogene;            
            }
        
        /* FACTURA */
        $sql = "SELECT f.fav_id,SUM(o.ord_venta) as ord_venta
                FROM ordenes o,cuentacorriente_cliente cc ,factura_venta f,grupo_ordenes g_o
                WHERE cc.cli_id = o.cli_id 
                AND f.fav_id = $fav_id
                AND o.estado = 1 
                AND cc.estado = 1 
                AND o.est_id >= 11
                AND f.estado = 1
                AND g_o.gru_id = f.gru_id
                AND g_o.gru_id = o.gru_id";
        
        $factura    = mysql_query($sql);
        $fila_factura = mysql_fetch_array($factura);
        


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
 function cargarCalendarios(cant) {
      for ($i = 1; $i <= cant; $i++) {
      /* Pago */
      $('#txtFechaTransferencia'+$i).datepick();
      $('#txtFechaEmision'+$i).datepick();   
      $('#txtFechaVto'+$i).datepick();
      }
      /* Retenciones */
      $('#txtFecha1').datepick();   // Ganancias
      $('#txtFecha2').datepick();   // IVA
      $('#txtFecha3').datepick();   // IIBB
      $('#txtFecha4').datepick();   // SUSS
      
  };
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
.Estilo1 {
	color: #FFFFFF
}
  </style>
</head>
  <body onLoad="cargarCalendarios(<?php echo $cantTipoPago; ?>)">
	
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
      
      
<table width="100%" cellpadding="5" class="listados">
          <tr class="titulo">
            <td colspan="5"> <?php echo($titulo)?> </td>
            <td width="36">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                
                </a>            
            </td>
          </tr>
          <tr>
            <td><big>Factura nro:</big></td>
            <td><big><b><?php echo $fila_factura["fav_id"]?></b></big></td>
            <td><big>Total Factura:</big></td>
            <td><big><b><?php echo $fila_factura["ord_venta"]?></b></big></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          
          <tr>
            <td>Agregar tipo Pago</td>

            <form action="form-alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" id="generadorPago">           
            <td><input name="cantidadTip" type="number" class="campos2" id="cantidadTip" required style="text-align:right" size="12"  min="1" max="30" value="<?php echo $cantTipoPago ?>" readOnly >
              
              <input type="submit" value="+" class="botones"  id="generarBoton" onClick="generarTipoPago('generadorPago','suma')"/>
              <input type="submit" value="- " class="botones"  id="generarBoton" onClick="generarTipoPago('generadorPago','resta')"/>
            </td>
            <td>&nbsp;</td>
            </form>
            <td>(Atención: se debe confeccionar la cantidad correcta en primera instancia)               
            </td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <form action="alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" enctype="multipart/form-data" >
           <!--Bucle Generador de Tipos Pagos-->
          
       <?php for ($i = 1; $i <= $cantTipoPago; $i++) { ?>

          <tr>
            <td width="139" bgcolor="#CDDCDA">Tipo de pago</td>
          <td width="179" bgcolor="#CDDCDA">
      <select name="comboTipoPago<?php echo $i ?>" class="campos2" id="comboTipoPago<?php echo $i ?>" onClick="filtroTipoDePago(value,<?php echo $i ?>)" required>
          <option value="0">Seleccione </option>
                <?php
                    $sql = "SELECT id,nombre FROM tipo_pago WHERE estado = 1";
                    $tipo_pago = mysql_query($sql);
                    while ($fila = mysql_fetch_array($tipo_pago)){
                
                ?>    
                    <option value="<?php echo $fila["id"]?>"><?php echo $fila["nombre"]?> </option>
                    <?php } ?>
                </select></td>
            <td width="108" bgcolor="#CDDCDA"><label>Nro Operación </label></td>
            <td width="343" bgcolor="#CDDCDA"><input name="txtNroOperacion<?php echo $i ?>" type="text" class="campos2" id="txtNroOperacion<?php echo $i ?>" required style="text-align:right" ></td>
            <td width="3" bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"></td>
          </tr>
          <tr class="filaCheque<?php echo $i ?>"   style="display:none;">
            <td width="139">Banco</td>
            <td width="179">
                    <select name="comboBanco<?php echo $i ?>" class="campos2" id="comboBanco<?php echo $i ?>" disabled required>
                    <?php        $sql = "SELECT ban_id,ban_nombre FROM banco WHERE estado = 1 ORDER BY ban_nombre";
                                $bancos     = mysql_query($sql);
                    while ($fila_banco = mysql_fetch_array($bancos)){
                
                ?>  
                        <option value="<?php echo $fila_banco["ban_id"]?>"><?php echo $fila_banco["ban_nombre"]?></option>
                  <?php } ?>
                                          </select></td>
            <td width="108">Sucursal</td>
            <td width="343"><input name="txtSucursal<?php echo $i ?>" type="text" class="campos2" id="txtSucursal<?php echo $i ?>" disabled required> </td>
            <td width="3">&nbsp;</td>
            <td width="36"></td>
          </tr>
          
          <tr class="filaCheque<?php echo $i ?>" style="display:none;">
            <td>Fecha emisión</td>
            <td><input name="txtFechaEmision<?php echo $i ?>" type="text" class="campos2" id="txtFechaEmision<?php echo $i ?>" disabled required style="text-align:center"></td>
            <td>Fecha vto:</td>
            <td><input name="txtFechaVto<?php echo $i ?>" type="text" class="campos2" id="txtFechaVto<?php echo $i ?>" disabled required style="text-align:center"></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr class="filaCheque<?php echo $i ?>" style="display:none;">
            <td>Firmante</td>
            <td><input name="txtFirmante<?php echo $i ?>" type="text" class="campos2" id="txtFirmante<?php echo $i ?>" disabled required></td>
            <td>CUIT firmante</td>
            <td><input name="txtCuit<?php echo $i ?>" type="text" class="campos2" id="txtCuit<?php echo $i ?>" disabled required style="text-align:right" ></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr class="filaTransferencia<?php echo $i ?>" style="display:none;">
            <td width="139">Cuenta</td>
            <td width="179"><select name="comboCuenta<?php echo $i ?>" class="campos2" id="comboCuenta<?php echo $i ?>" disabled required>
              <?php
                     $sql = "SELECT id,nombre FROM cuentabanco";
                     $cuentabanco = mysql_query($sql);
                    while ($fila_cuenta = mysql_fetch_array($cuentabanco)){
                ?>
              <option value="<?php echo$fila_cuenta["id"]?>"><?php echo $fila_cuenta["nombre"]?></option>
              <?php } ?>
            </select></td>
            <td width="108">Fecha transf</td>
            <td width="343"><input name="txtFechaTransferencia<?php echo $i ?>" type="text" class="campos2" id="txtFechaTransferencia<?php echo $i ?>" disabled required style="text-align:center"></td>
            <td width="3">&nbsp;</td>
            <td width="36"></td>
          </tr>
          <tr>
            <td>Importe</td>

            <td><input name="txtImportePago<?php echo $i ?>" type="text" disabled required class="campos2" id="txtImportePago<?php echo $i ?>" onChange="actualizarDetallePago('<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)" value="0" style="text-align:right">
            </td>

            <td>Adjuntar archivo</td>

            <td><input type="file" name="userfile<?php echo $i ?>" id="userfile<?php echo $i ?>"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            
          </tr>
          <?php } ?>
                  
          </table>
          
<div class="retenciones">
          <form action="alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" enctype="multipart/form-data" >
          <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
          <tr>
            <td width="7%"></td>
            <td width="18%"></td>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkGanancias" id="chkGanancias" onClick="habilitarRetencionesYActualizarDetalle('chkGanancias',1,'<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)">
            Ganancias            </td>
            <td width="75%" bgcolor="#CDDCDA">&nbsp;</td>
            </tr>          
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha1" type="text" class="campos2" id="txtFecha1"  size="12"  min="0" required disabled style="text-align:center" /></td>
            <td>Prefijo :
              <input name="txtPrefijo1" type="text" class="campos2" id="txtPrefijo1" style="text-align:right" size="5"  min="0"  disabled  />
            <label>Nro :
              <input name="txtNro1" type="text" class="campos2" id="txtNro1" size="20" required disabled style="text-align:right">
            </label></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><label>
                    <input name="txtImporte1" type="text" disabled class="campos2" id="txtImporte1" onChange="actualizarDetallePago('<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)" value="0" size="8" style="text-align:right">
            </label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIva" id="chkIva" onClick="habilitarRetencionesYActualizarDetalle('chkIva',2,'<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)">
            IVA            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha2" type="text" class="campos2" id="txtFecha2"  size="12"  min="0" required disabled style="text-align:center"/></td>
            <td>Prefijo :
              
              <input name="txtPrefijo2" type="text" class="campos2" id="txtPrefijo2" style="text-align:right" size="5"  min="0" disabled   />
              Nro :
<input name="txtNro2" type="text" class="campos2" id="txtNro2" size="20" disabled required style="text-align:right"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte2" type="text" disabled class="campos2" id="txtImporte2" onChange="actualizarDetallePago('<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago ?>)" value="0" size="8" style="text-align:right"></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %  
              
              <select name="comboIva" class="campos2" id="comboIva" disabled required>
                  <?php
                    while ($fila_iva = mysql_fetch_array($iva)){
                ?>
                  <option value="<?php echo $fila_iva["idiva"]?>"><?php echo $fila_iva["valor"]?></option>
                  <?php }?>
              </select>            </td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIIBB" id="chkIIBB" onClick="habilitarRetencionesYActualizarDetalle('chkIIBB',3,'<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)">
            IIBB            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha3" type="text" class="campos2" id="txtFecha3"  size="12"  min="0" required disabled style="text-align:center" /></td>
            <td>Prefijo :
              <input name="txtPrefijo3" type="text" class="campos2" id="txtPrefijo3" style="text-align:right" size="5"  min="0" disabled   />
Nro :
<input name="txtNro3" type="text" class="campos2" id="txtNro3" size="20" disabled required style="text-align:right"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte3" type="text" disabled class="campos2" id="txtImporte3" onChange="actualizarDetallePago('<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago ?>)" value="0" size="8" style="text-align:right"></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkSUSS" id="chkSUSS" onClick="habilitarRetencionesYActualizarDetalle('chkSUSS',4,'<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago; ?>)">
            SUSS            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha4" type="text" class="campos2" id="txtFecha4"  size="12"  min="0" required disabled style="text-align:center" /></td>
            <td>Prefijo :
              <input name="txtPrefijo4" type="text" class="campos2" id="txtPrefijo4" style="text-align:right" size="5"  min="0" disabled />
Nro :
<input name="txtNro4" type="text" class="campos2" id="txtNro4" size="20" disabled required style="text-align:right"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte4" type="text" disabled class="campos2" id="txtImporte4" onChange="actualizarDetallePago('<?php echo $fila_factura["ord_venta"]?>',<?php echo $cantTipoPago ?>)" value="0" size="8" style="text-align:right"></td>
            <td>Provincia:
              <select name="comboProvincias" class="campos2" id="comboProvincias" disabled>
                <?php
                    while ($fila_provincia = mysql_fetch_array($provincias)){
                ?>
                <option value="<?php echo $fila_provincia["id"]?>"><?php echo $fila_provincia["nombre"]?> </option>
                <?php } ?>
              </select>
              Jurisdicción: 
              <label>
              <input name="txtJurisdiccion" type="text" class="campos2" id="txtJurisdiccion" size="9">
              </label></td>
            </tr>
          <tr>
            <td colspan="3" bgcolor="#0099CC"><label></label>
            <div align="center"><span class="Estilo1">Detalle del pago</span></div></td>
          </tr>
          <tr>
            <td>Depósito</td>
            <td><label>
              <input name="txtDeposito" type="text" class="campos2" id="txtDeposito" value="0" disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Ganancias</td>
            <td><label>
              <input name="txtGanancias" type="text" class="campos2" id="txtGanancias"  value="0"disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>IVA</td>
            <td><label>
              <input name="txtIva" type="text" class="campos2" id="txtIva"  value="0"disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>IIBB</td>
            <td><label>
              <input name="txtIIBB" type="text" class="campos2" id="txtIIBB" value="0" disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>SUSS</td>
            <td><label>
              <input name="txtSUSS" type="text" class="campos2" id="txtSUSS"  value="0"disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Total</td>
            <td><label>
              <input name="txtTotal" type="text" class="campos2" id="txtTotal" value="0" disabled style="text-align:right"  size="12">
            </label></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp; <input type="submit" value="Registrar pago" class="botones" style="visibility:visible" id="botonRegistrar" />
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
