<?php

    header('Content-Type: text/html; charset=utf-8');
    $titulo = "Detalle del pago y retenciones";
        include("validar.php");
        include("conexion.php");
        include("Modelo/modeloFacturaVenta.php");
        include("Modelo/modeloCobrosDetalleRetencion.php");
        include("Modelo/modeloCobrosDetallePago.php");
        include("Modelo/modeloNotaCredito.php");
         include("funciones.php");
        $fav_id     =  $_GET["grupo_fav"];
        $action     =  $_GET["action"];
        
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
        $factura      = getListadoFacturasWithGrupo($fav_id);
        
        
        /* DETALLE PAGO */
        $detallePagos       = getDetallePagoByFavId($fav_id);
        /* DETALLE RETENCIONES */
        $detalleRetenciones = getDetalleRetencionByFavId($fav_id);
        //Total
        $montoTotal=0;
        //NC de PAgo
        $NC= getNCIDWhitgrupoFavId($fav_id);


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
      
      
<table width="100%" cellpadding="5" class="listados">
          <tr class="titulo">
            <td colspan="5"> <?php echo($titulo)?> </td>
            <td width="36">
                <a href="index-admin.php">
                    <img src="images/home.png"  alt="inicio" title="Volver al panel" width="32" height="32" border="none" />                </a>            </td>
          </tr>
          
              
            <?php while($fila_factura = mysql_fetch_array($factura)){?>  
          <tr>
            <td><big>Factura nro:</big></td>
            <td>              <a href="ver-alta-factura.php?fav_id=<? echo $fila_factura["fav_id"];?>" target="_blank">
<big><b><?php echo $fila_factura["cod_factura_venta"]?></b></big></a></td>
            <td><big>Total Factura:</big></td>
            <td><big><b><?php  $total=mysql_fetch_array(getTotalAPagarConIva($fila_factura["fav_id"]));echo number_format($total['total'], 2, ',', '.')?></b></big></td>
            <td>&nbsp;</td>
            <td></td>
            </tr>
            <? $montoTotal+=$total['total'];
            
            }?>
            <tr><td> <b><big>TOTAL: <? echo number_format($montoTotal, 2, ',', '.');   ?></big></b></td> </tr>
          <form action="lista-facturas.php" method="post" enctype="multipart/form-data" >
           <!--Bucle Generador de Tipos Pagos-->
          
       

          
          <?php while($fila_pago = mysql_fetch_array($detallePagos)){ ?>
          <tr>
            <td width="139" bgcolor="#CDDCDA">Tipo de pago</td>
          <td width="179" bgcolor="#CDDCDA"><?php echo $fila_pago["pago"]?></td>
            <td width="108" bgcolor="#CDDCDA"><label>Nro Operación </label></td>
            <td width="343" bgcolor="#CDDCDA"><?php echo $fila_pago["nro"]?></td>
            <td width="3" bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA"></td>
          </tr>
          
          <?php if ($fila_pago["tipo_pago_id"] == 1){ ?>
          <tr>
            <td width="139">Banco</td>
            <td width="179"><?php echo $fila_pago["ban_nombre"]?></td>
            <td width="108"><label>Sucursal</label></td>
            <td width="343"><?php echo $fila_pago["sucursal"]?></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td width="139">Fecha emision</td>
            <td width="179"><?php echo mfecha($fila_pago["fecha_emision"]) ?></td>
            <td width="108"><label>Fecha vencimiento</label></td>
            <td width="343"><?php echo mfecha($fila_pago["fecha_vto"]) ?></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          
          <tr>
            <td width="139">Firmante</td>
            <td width="179"><?php echo $fila_pago["firmante"]?></td>
            <td width="108"><label>CUIT Firmante</label></td>
            <td width="343"><?php echo $fila_pago["cuit_firmante"]?></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td width="139">Importe</td>
            <td width="179"><?php echo number_format($fila_pago["importe"], 2, ',', '.')?></td>
            <td width="108"><label></label></td>
            <td width="343"></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          
          <?php } ?>
          
  
          <?php if ($fila_pago["tipo_pago_id"] == 2){ ?>
          <tr>
            <td width="139">Importe</td>
            <td width="179"><?php echo number_format($fila_pago["importe"], 2, ',', '.')?></td>
            <td width="108"><label> </label></td>
            <td width="343"></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          
          <?php } ?>
          
          <?php if ($fila_pago["tipo_pago_id"] == 3){ ?>
          <tr>
            <td width="139">Cuenta</td>
            <td width="179"><?php echo $fila_pago["nombre"]?></td>
            <td width="108"><label>Fecha transferencia</label></td>
            <td width="343"><?php echo mfecha($fila_pago["fecha_transferencia"])?></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          <tr>
            <td width="139">Importe</td>
            <td width="179"><?php echo number_format($fila_pago["importe"], 2, ',', '.')?></td>
            <td width="108"><label></label></td>
            <td width="343"></td>
            <td width="3">&nbsp;</td>
            <td></td>
          </tr>
          
          <?php } ?>
          
          
          
          
          <?php } ?>
          
          
          </table>
      
          <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Nota de Crédito</div></td>
        </tr>
        <!-- Tabla dinamica -->
        <?php while($fila_NC = mysql_fetch_array($NC)){
            $FilaDetalleNC=getmontoTotalWhitNCID($fila_NC["nrc_id"]);
            $montoNC=mysql_fetch_array($FilaDetalleNC);
            ?>
            
          <tr>
            <td bgcolor="#CDDCDA">N° <?php echo $fila_NC["nrc_codigo"]?>            </td>
            <td width="75%" bgcolor="#CDDCDA" ><?php echo number_format($montoNC["monto"],2,',','.')?> </td>
            </tr>          
  
        
        <?php } ?>
 
      </table>
       
<div class="retenciones">
        
          
              <table class="listados">
          <tr>
            <td colspan="3" bgcolor="#0099CC"><div align="center" class="Estilo1">Retenciones</div></td>
        </tr>
        <!-- Tabla dinamica -->
        <?php while($fila_retencion = mysql_fetch_array($detalleRetenciones)){?>
        
            <tr>
            <td width="7%"></td>
            <td width="18%"></td>
          <tr>
            <td bgcolor="#CDDCDA">&nbsp;</td>
            <td bgcolor="#CDDCDA">      <?php echo $fila_retencion["nombre"]?>            </td>
            <td width="75%" bgcolor="#CDDCDA">&nbsp;</td>
            </tr>          
          <tr>
            <td>Fecha:</td>
            <td><?php echo $fila_retencion["ret_fecha"]?></td>
            <td>Prefijo :<?php echo $fila_retencion["ret_prefijo"]?>
              
              <label>/ Nro :  <?php echo $fila_retencion["ret_codigo"]?>          </label></td>
            </tr>
          <tr>
            <td>Importe:</td>
            <td><label><?php echo $fila_retencion["ret_importe"]?></label></td>
            <td><?php if($fila_retencion["ret_id"] == 2){
                        echo "IVA: ".$fila_retencion["valor"];
                    }
                      if($fila_retencion["ret_id"] == 4){
                        echo "Provincia: ".$fila_retencion["provincia"]." / "."Jurisdicción: ".$fila_retencion["jurisdiccion"];  
                      }  
                        
                ?></td>
            </tr>
  
        
        <?php } ?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" class="pie_lista"><div align="center">
             <? if($action ==1) { ?>
                  <a href="reporte-retenciones.php">  
                    <? }else{?>
                  <a href="form-seleccionar-cliente-pago.php">
                      <? } ?>
                      
                  <input type="button" value="Volver" class="botones" /></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                                  <a href="form-generar-pago-factura.php">
                    <input type="button" value="Otro Pago" class="botones" /></a>
            </div></td>

          </tr>
      </table>
              
      
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
