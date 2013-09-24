<head>
<?php
include("../validar.php");
include("../conexion.php"); 
include("../funciones.php");
include("../Modelo/modeloOrdenes.php");
include("../Modelo/modeloRubros.php");
include("../Modelo/modeloClientes.php");
include("../Modelo/modeloAbonoDeOrden.php");
//Datos
  $NombreSuc=$_GET["NombreSuc"];
  $dia=date("d-m-Y");
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Planilla_Facturacion_de_$NombreSuc.$dia.xls");
?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>

<style type="text/css">
body, table {
      font-family:Arial, sans-serif;
      font-size: 10pt;
      aling:"center";
      vertical-align: middle;
      text-align: center
}
.encabezadoTabla {
    color:white;
    aling:"center";
    scope:"col";
}
</style>

</head>
<body>
<?php
 $favId=$_GET["fav_id"]; 
 $favCodigo=$_GET["favCodigo"];  
$result=getOrdenesWithFavId($favId);
// INGRESAR NOMBRE 
$nombre=" Planilla de Facturaci&oacute;n de $NombreSuc";
?>
<b><big>SEMANTEC - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center"   scope="col" CELLPADDING=1 CELLSPACING=1 frame="above">
<TR bgcolor="#9C3B0E" class="encabezadoTabla">

    
<TD aling="center"><b> </b></TD>
<TD style="mso-number-format:'dd/mm/yyyy' "   > <b>&nbsp; &nbsp; &nbsp; &nbsp; FECHA &nbsp; &nbsp; &nbsp; &nbsp;</b></TD>
<TD aling="center"><b>SUC</b></TD>
<TD  scope="col" ><b>RUBRO</b></TD>
<TD style="mso-number-format:'@'" ><b>DESCRIPCION</b></TD>
<TD style="mso-number-format:'0.00';" ><b>MONTO</b></TD>
<TD ><b>TICKET</b></TD>
<TD ><b>FACTURA</b></TD>
<TD ><b>PRESUPUESTO</b></TD>

</TR>

<?php  $subtotal=0;
while($row = mysql_fetch_array($result)) {
    //Datos de impresion
        //valor de venta de la orden
              $valor_ventaDeOrden=0;
              $valor_ventaDeOrden=$row["ord_venta"];
        //valor de Abono

              if(($row["es_abono"])==1){ 
                 $consult=getAbonosDeOrdenWithCliId($row["ord_id"]);
                 $filaAbono = mysql_fetch_array($consult);
                 $valor_venta_Abono=$filaAbono["valor_venta"]; 
                
        //         echo "<b>valor del abono : $valor_venta_Abono</b>";
                 $valor_ventaDeOrden=$valor_ventaDeOrden+$valor_venta_Abono;
              }
        //---------------------------------------
         $filaDeRubro = mysql_fetch_array(getRubroNameById($row["rub_id"]));
         $valorVenta= "$";  
         $subtotal+=$valor_ventaDeOrden;
         $valorVenta.= number_format($valor_ventaDeOrden, 2, ',', '.');
         $presupuesto=$row["presupuesto"];
         if($presupuesto==''){
             $presupuesto='-';
         }
         $cli_idBusqueda=$row["cli_id"]; 
         $Sucursal= mysql_fetch_array(getClienteSucursalConID($cli_idBusqueda)); 
         $SUCC=$row["provincia"]." (".$Sucursal["sucursal"].")";
    //fin de datos de impresion
printf("<tr>
<td><font color=black align='middle'> &nbsp;%s </font></td>
<td style=mso-number-format:'dd/mm/yyyy'>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td >&nbsp;%s&nbsp;</td>
<td style=mso-number-format: $0.00 >%s</td>
<td>&nbsp;%s&nbsp;</td>
<td >&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>

</tr>" ,SEMANTEC,
         '',
       // $row["provincia"],
        $SUCC,
        $filaDeRubro["rub_nombre"],
        $row["ord_descripcion"],
        $valorVenta,
        $row["ord_codigo"],
        $favCodigo,
        $presupuesto
        );
}
mysql_free_result($result);
mysql_close();  //Cierras la Conexión
?>
<TR bgcolor="#DCD8D6" class="encabezadoTabla"  height="8" border ="0">
    
        
<TD border ="0"></TD>
<TD > </TD>
<TD ></TD>
<TD  ></TD>
<TD ></TD>
<TD  ></TD>
<TD ></TD>
<TD ></TD>
<TD ></TD> 
</tr>
</table>
<b><? echo 'Subtotal $',(number_format($subtotal, 2, ',', '.')); ?></b>
</body>
</html>
?>
