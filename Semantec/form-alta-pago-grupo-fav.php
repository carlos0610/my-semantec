<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de registro de pago y retenciones";
        include("validar.php");
        include("conexion.php");
        include("Modelo/modeloFacturaVenta.php");
        include("Modelo/modeloBanco.php");
        include("Modelo/modeloProvincias.php");
        include("Modelo/modeloClientes.php");
        include("Modelo/modeloNotaCredito.php");
        $fav_id     =  $_GET["fav_id"]; 
        $ccc_id     =  $_GET["ccc_id"];
        /*TIPOS DE PAGO*/
        
        $sql = "SELECT id,nombre FROM tipo_pago WHERE estado = 1";
        $tipo_pago = mysql_query($sql);
        
        /* BANCOS */
        $bancos     = getListarTodoBancos();
       
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
        
         /* Cantidad de IIBB */
        
        $cantIIBB=1;
        $cantIIBBgene  =  $_POST["cantidadIIBB"]; 
        if($cantIIBBgene>0)
            {
                  $cantIIBB=$cantIIBBgene;            
            }   
            
        /* Cliente */        
            $cli_id=$_GET["cli_id"]; 
            $Cliente=getClienteSucursalConID($cli_id);
            $filaCliente=mysql_fetch_array($Cliente); 
        
        /* Facturas seleccionadas*/
        $cantTotalFav=$_GET["cant"];
        
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
 function cargarCalendarios(cant, cantIIBB) {
      for ($i = 1; $i <= cant; $i++) {
      /* Pago */
      $('#txtFechaTransferencia'+$i).datepick();
      $('#txtFechaEmision'+$i).datepick();   
      $('#txtFechaVto'+$i).datepick();
      }
      /* Retenciones */
      $('#txtFecha1').datepick();   // Ganancias
      $('#txtFecha2').datepick();   // IVA
      $('#txtFecha3').datepick();   // SUSS   
      $numeroRetencion=4;
      for ($i = 1; $i <= cantIIBB; $i++) {
         $('#txtFecha'+$numeroRetencion).datepick();   // IIBB
         $numeroRetencion++;
      }
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
  <body onLoad="cargarCalendarios(<?php echo $cantTipoPago; ?>,<?php echo $cantIIBB; ?>)">
	
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


      <h2><?php echo $de=($filaCliente["cli_nombre"]); ?></h2>
      
      
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
            <td width="6"><big>N° Factura</big></td> <td></td> <td></td>  
 <?   $i=0; $totalFavs=0; 
 while ($i < $cantTotalFav) 
{ include("conexion.php");  // borrrARR SACAR ESTE CONEXION!!!!1
    $i++; //echo $i,'---';
    $CodigoFav=$_POST["o$i"];
        $filaFactura = getTotalAPagarConIva($CodigoFav);
       $facturaTotal = mysql_fetch_array($filaFactura);
       $totalFavs+=$facturaTotal["total"];
       
       
        echo "<tr bgcolor=#CDDCDA><td width=508 >",getFacturasCodigoWhitID($CodigoFav),"</td>";
        echo "<td style=text-align:right>",number_format( $facturaTotal["total"],2,',','.'),"</td>";
 
         echo"</tr>";

}

?>


            <td><big><b>Total:</b></big>  </td> 
            <td><input name="totalFavs"  type="hidden2"  id="totalFavs" value="<? echo  $totalFavs ?>" readonly style="background-color:white; border: solid 0px ;
height: 25px; font-size:18px; vertical-align:6px"></td>
            
<td width=208 ><b>Total Factura:</b></td>
            <td style="text-align:right;"><big><b><?php echo number_format($totalFavs,2,',','.') ?></b></big></td>
            
            <td></td>
          </tr>
          
          <tr>
            <td>Agregar tipo Pago</td>

            <form action="form-alta-pago-grupo-fav.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>&cant=<?php echo $cantTotalFav ?>&cli_id=<?php echo $cli_id ?>" method="post" id="generadorPago">    
<?   $i=0;   // cuando agrega items de pagos debe pasar las facturas seleccionadas        
 while ($i < $cantTotalFav) 
{ 
    $i++; //echo $i,'---';
    $CodigoFav=$_POST["o$i"]; ?>
    <input type="hidden" name="o<?php echo $i; ?>"  id="o<?php echo $i; ?>" value="<?php echo ($CodigoFav); ?>" > 
    <?
}
?>
                
            <td>
              <input name="cantidadTip" type="number" class="campos2" id="cantidadTip" required style="text-align:right" size="12"  min="1" max="30" value="<?php echo $cantTipoPago ?>" readOnly >           
              <input type="submit" value="+" class="botones"  id="generarBoton" onClick="generarTipoPago('generadorPago','suma')"/>
              <input type="submit" value="- " class="botones"  id="generarBoton" onClick="generarTipoPago('generadorPago','resta')"/>
            </td>
            <td>Agregar IIBB</td>
            <td>
              <input name="cantidadIIBB" type="number" class="campos2" id="cantidadIIBB" required style="text-align:right" size="12"  min="1" max="30" value="<?php echo $cantIIBB ?>" readOnly >           
              <input type="submit" value="+" class="botones"  id="generarBoton" onClick="generarIIBB('generadorPago','suma')"/>
              <input type="submit" value="- " class="botones"  id="generarBoton" onClick="generarIIBB('generadorPago','resta')"/></td>
            </form>
          
            <td>              
            </td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
     <form id="altaPagoFav" action="alta-pago-grupo-fav.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>&cantIIBB=<?php echo $cantIIBB ?>&cant=<?php echo $cantTotalFav ?>&cli_id=<?php echo $cli_id ?>&totalFavs=<?php echo $totalFavs ?>" method="post" enctype="multipart/form-data" >
        <?   $i=0;   // guardo las facturas a pagar   
 while ($i < $cantTotalFav) 
{ 
    $i++; //echo $i,'---';
    $CodigoFav=$_POST["o$i"]; ?>
    <input type="hidden" name="ofav<?php echo $i; ?>"  id="o<?php echo $i; ?>" value="<?php echo ($CodigoFav); ?>" > 
    <?
}
?>
         
            
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

            <td><input name="txtImportePago<?php echo $i ?>" type="text" disabled required class="campos2" id="txtImportePago<?php echo $i ?>" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>)" value="0" style="text-align:right">
            </td>

            <td>Adjuntar archivo</td>

            <td><input type="file" name="userfile<?php echo $i ?>" id="userfile<?php echo $i ?>"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            
          </tr>
          <?php } ?>
                  
          </table>
      
       
      <table class="listados">
          <tr>
              <td colspan="9" bgcolor="#0099CC"><div align="center" class="Estilo1">Nota de Credito</div></td>
          </tr>
     <tr>
           
     <?   $i=0; $totalFavs=0; 
 $arregloNC=array();// arreglo para no repetir NC q contengan facturas en el grupo seleccionado
 while ($i < $cantTotalFav) 
{ include("conexion.php");  // borrrARR SACAR ESTE CONEXION!!!!1
    $i++; //echo $i,'---';
    $CodigoFav=$_POST["o$i"];
        $filaFactura = getTotalAPagarConIva($CodigoFav);
       $facturaTotal = mysql_fetch_array($filaFactura);
       $totalFavs+=$facturaTotal["total"];
       $filaNC=mysql_fetch_array((getMontoTotalWhitFavId($CodigoFav)));
       $valorIva=$filaNC['monto']*0.21;
        if((   $filaNC['monto'] >0) and  !(in_array($filaNC['nrc_id'], $arregloNC))and (   $filaNC['codigo_nc'] != '') ){  
            
        echo "<tr bgcolor=#CDDCDA>
            <td width=3%><input type=checkbox name=chkNC$i value=$filaNC[nrc_id] id=chkNC$i onClick= ActualizarTotalPagoFac('chkNC$i',$filaNC[monto]+$valorIva,$cantTipoPago) > </td>  
        <td width=17% style=text-align:right> N° NC: ",($filaNC['codigo_nc']),"</td>  <td style=text-align:right ><b>",number_format( $filaNC['monto']+$valorIva,2,',','.'),"</b></td> ";
            
        echo "<td style=text-align:right> N° Factura: ",getFacturasCodigoWhitID($CodigoFav),"</td>";
        echo "<td style=text-align:right>",number_format( $facturaTotal["total"],2,',','.'),"</td>";
        echo " <input type=hidden name=facDeNC$i id=facDeNC$i value=$CodigoFav > ";
 
         
         $arregloNC[]=($filaNC['nrc_id']); 

         }
         echo"</tr>";

}

?>

          </tr>
    
      </table>
     
<div class="retenciones">
          <form  action="alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" enctype="multipart/form-data" >
          <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
          <tr>
            <td width="7%"></td>
            <td width="18%"></td>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkGanancias" id="chkGanancias" onClick="habilitarRetencionesYActualizarDetalle('chkGanancias',1,'<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>)">
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
                    <input name="txtImporte1" type="text" disabled class="campos2" id="txtImporte1" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>)" value="0" size="8" style="text-align:right">
            </label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIva" id="chkIva" onClick="habilitarRetencionesYActualizarDetalle('chkIva',2,'<?php echo $fila_factura["total"]?>',<?php echo $cantTipoPago; ?>)">
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
            <td><input name="txtImporte2" type="text" disabled class="campos2" id="txtImporte2" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago ?>)" value="0" size="8" style="text-align:right"></td>
            <td style="visibility:hidden">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %  
              
              <select name="comboIva" class="campos2" id="comboIva" disabled required>
                  <?php
                    while ($fila_iva = mysql_fetch_array($iva)){
                ?>
                  <option value="<?php echo $fila_iva["idiva"]?>"><?php echo $fila_iva["valor"]?></option>
                  <?php }?>
              </select>            </td>
            </tr>
             <!-- SUSS--->
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkSUSS" id="chkSUSS" onClick="habilitarRetencionesYActualizarDetalle('chkSUSS',3,'<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>)">
            SUSS            </td>
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
            <td><input name="txtImporte3" type="text" disabled class="campos2" id="txtImporte3" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago ?>)" value="0" size="8" style="text-align:right"></td>
            <td>&nbsp;</td>
            </tr>
            
          <!-- IIBB-------------->
          <?php  $NumeroDeRetencion=4; 
             for ($i = 1; $i <= $cantIIBB; $i++) { ?>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"><input type="checkbox" name="chkIIBB<? echo $i ?>" id="chkIIBB<? echo $i ?>" onClick="habilitarRetencionesYActualizarDetalle('chkIIBB<? echo $i ?>',<? echo $NumeroDeRetencion; ?>,'<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>)">
            IIBB            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td><input name="txtFecha<? echo $NumeroDeRetencion ?>" type="text" class="campos2" id="txtFecha<? echo $NumeroDeRetencion ?>"  size="12"  min="0" required disabled style="text-align:center" /></td>
            <td>Prefijo :
              <input name="txtPrefijo<? echo $NumeroDeRetencion ?>" type="text" class="campos2" id="txtPrefijo<? echo $NumeroDeRetencion ?>" style="text-align:right" size="5"  min="0" disabled />
Nro :
<input name="txtNro<? echo $NumeroDeRetencion ?>" type="text" class="campos2" id="txtNro<? echo $NumeroDeRetencion ?>" size="20" disabled required style="text-align:right"></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><input name="txtImporte<? echo $NumeroDeRetencion ?>" type="text" disabled class="campos2" id="txtImporte<? echo $NumeroDeRetencion ?>" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago ?>,<?php echo $cantIIBB ?>)" value="0" size="8" style="text-align:right"></td>
            <td>Provincia:
              <select name="comboProvincias<? echo $NumeroDeRetencion ?>" onChange="obtenerJurisdiccion(<? echo $NumeroDeRetencion?>);" class="campos2" id="comboProvincias<? echo $NumeroDeRetencion ?>" disabled>
                <option value="0">Seleccione </option>
                  <?php
                    $provincias=getProvincias();
                    while ($fila_provincia = mysql_fetch_array($provincias)){
                ?>
                <option value="<?php echo $fila_provincia["id"]?>"><?php echo $fila_provincia["nombre"]?> </option>
                <?php } ?>
              </select>
              Jurisdicción: 
              <label>
              <input name="txtJurisdiccion<? echo $NumeroDeRetencion ?>" type="text" class="campos2" id="txtJurisdiccion<? echo $NumeroDeRetencion ?>" style="text-align:right" size="9" required disabled readOnly>
              </label></td>
            </tr>
            <?php   $NumeroDeRetencion++; 
            } ?> 
          <!-- IIBB  FIN--->
          <!-- OTRAS DEDUCCIONES INICIO  --->         
           <tr>
            <td colspan="3" bgcolor="#0099CC"><label></label>
            <div align="center"><span class="Estilo1">Otras deducciones</span></div></td>
          </tr>
          <tr>
            <td><div align="center">
                <input type="checkbox" name="chkComisionBancaria" id="chkComisionBancaria" onClick="habilitarDeducciones('chkComisionBancaria','<?php echo $totalFavs ?>',<?php echo $cantTipoPago; ?>,<?php echo $cantIIBB ?>)">
            </div></td>
            <td><label>
              <input name="txtComisionBancaria" type="text" class="campos2" id="txtComisionBancaria" value="0" disabled style="text-align:right"  size="12" onChange="actualizarDetallePago('<?php echo $totalFavs ?>',<?php echo $cantTipoPago ?>,<?php echo $cantIIBB ?>)">
            </label></td>
            <td>Comisión por transferencia bancaria</td>
          </tr>        
          <!-- OTRAS DEDUCCIONES FIN  --->  
          <!-- DETALLE DE PAGO  INICIO--->
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
            <td>SUSS</td>
            <td><label>
              <input name="txtSUSS" type="text" class="campos2" id="txtSUSS"  value="0"disabled style="text-align:right"  size="12">
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
            <td>Comisión </td>
            <td><label>
              <input name="txtComision" type="text" class="campos2" id="txtComision" value="0" disabled style="text-align:right"  size="12">
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
            <td>&nbsp; <input type="submit" value="Registrar pago" class="botones" style="visibility:hidden" id="botonRegistrar"  />
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
