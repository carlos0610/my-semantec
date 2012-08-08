<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Formulario de registro de pago y retenciones";
        include("validar.php");
        include("conexion.php");
        include("Modelo/modeloFacturaVenta.php");
        include("Modelo/modeloCobrosDetalleRetencion.php");
        
        $fav_id     =  $_GET["fav_id"]; 
        $ccc_id     =  $_GET["ccc_id"];
        
        /*TIPOS DE PAGO*/
        
        $sql = "SELECT id,nombre FROM tipo_pago WHERE estado = 1";
        $tipo_pago = mysql_query($sql);
        
        /* BANCOS */
        //$bancos     = 
        
        
        
        /* IVA */
        $sql = "SELECT idiva,valor FROM iva WHERE idiva in (2,3)";
        $iva        = mysql_query($sql);
        
        
        /* Cantidad de Tipo Pago */
        
        $cantTipoPago=1;
        $cantTipoPagogene  =  $_POST["cantidadTip"]; 
        if($cantTipoPagogene>0)
            {
                  $cantTipoPago=$cantTipoPagogene;            
            }
        
        /* FACTURA */
        $factura      = getListadoFacturasPagadasYno($fav_id);
        $fila_factura = mysql_fetch_array($factura);
        
        
        /* DETALLE RETENCIONES */
        $detalleRetenciones = getDetalleRetencionByFavId($fav_id);
         
        


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
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          <tr>
            <td><big>Factura nro:</big></td>
            <td><big><b><?php echo $fila_factura["fav_id"]?></b></big></td>
            <td><big>Total Factura:</big></td>
            <td><big><b><?php echo $fila_factura["ord_venta"]?></b></big></td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <form action="alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" enctype="multipart/form-data" >
           <!--Bucle Generador de Tipos Pagos-->
          
       <?php for ($i = 1; $i <= $cantTipoPago; $i++) { ?>

          <tr>
            <td width="139" bgcolor="#CDDCDA">Tipo de pago</td>
          <td width="179" bgcolor="#CDDCDA">&nbsp;</td>
            <td width="108" bgcolor="#CDDCDA"><label>Nro Operación </label></td>
            <td width="343" bgcolor="#CDDCDA">&nbsp;</td>
            <td width="3" bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"></td>
          </tr>
          <tr class="filaCheque<?php echo $i ?>"   style="display:none;">
            <td width="139">Banco</td>
            <td width="179">&nbsp;</td>
            <td width="108">Sucursal</td>
            <td width="343">&nbsp;</td>
            <td width="3">&nbsp;</td>
            <td width="36"></td>
          </tr>
          
          <tr class="filaCheque<?php echo $i ?>" style="display:none;">
            <td>Fecha emisión</td>
            <td>&nbsp;</td>
            <td>Fecha vto:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr class="filaCheque<?php echo $i ?>" style="display:none;">
            <td>Firmante</td>
            <td>&nbsp;</td>
            <td>CUIT firmante</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
          </tr>
          <tr class="filaTransferencia<?php echo $i ?>" style="display:none;">
            <td width="139">Cuenta</td>
            <td width="179">&nbsp;</td>
            <td width="108">Fecha transf</td>
            <td width="343">&nbsp;</td>
            <td width="3">&nbsp;</td>
            <td width="36"></td>
          </tr>
          <tr>
            <td>Importe</td>

            <td>&nbsp;</td>

            <td>Archivo</td>

            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php } ?>
          </table>
          
<div class="retenciones">
          <form action="alta-pago.php?fav_id=<?php echo $fav_id ?>&cantTipoPago=<?php echo $cantTipoPago ?>&ccc_id=<?php echo $ccc_id ?>" method="post" enctype="multipart/form-data" >
          <?php while($fila_retencion = mysql_fetch_array($detalleRetenciones)){ ?>
              <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
          <tr>
            <td width="7%"></td>
            <td width="18%"></td>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA">            Ganancias            </td>
            <td width="75%" bgcolor="#CDDCDA">&nbsp;</td>
            </tr>          
          <tr>
            <td>Fecha:</td>
            <td><?php echo $fila_retencion["ret_fecha"]?></td>
            <td>Prefijo :<?php echo $fila_retencion["ret_prefijo"]?>
              
              <label>Nro :  <?php echo $fila_retencion["ret_codigo"]?>          </label></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><label></label></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA">            IVA            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td>&nbsp;</td>
            <td>Prefijo :
              
              
              Nro :<?php echo $fila_retencion["ret_codigo"]?></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><?php echo $fila_retencion["ret_importe"]?></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; %  :            </td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA">            IIBB            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td>&nbsp;</td>
            <td>Prefijo :
              
Nro :</td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><?php echo $fila_retencion["ret_importe"]?></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA">            SUSS            </td>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            </tr>
          <tr>
            <td>Fecha:</td>
            <td>&nbsp;</td>
            <td>Prefijo :
              
Nro :</td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td>&nbsp;</td>
            <td>Provincia:            </td>
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
              <?php } ?>
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
