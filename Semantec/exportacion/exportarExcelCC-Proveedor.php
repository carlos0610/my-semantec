<?php
include("../validar.php");
include("../conexion.php"); 
include("../funciones.php");
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 $proveedor=$_GET["nombre"]; 
header("content-disposition: attachment;filename=CC-Proveedor-$proveedor.xls");
?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
</head>
<body>
<?php
// INGRESAR NOMBRE 

$nombre=" Cuenta corriente de $proveedor";
?>
<b><big>SEMANTEC - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center" CELLPADDING=1 CELLSPACING=1>
<TR bgcolor="#A9E2F3">

    
    <TD><b>C&oacute;digo de orden</b></TD>
<TD><b>Cliente</b></TD>
<TD><b>Descripci&oacute;n</b></TD>
<TD style="mso-number-format:'dd/mm/yyyy' "   ><b>Recepci&oacute;n OT</b></TD>
<TD><b>Ord costo</b></TD>
<TD style="mso-number-format:'0.00';" align="right"><b>Abono</b></TD>
<TD style="mso-number-format:'0.00';" align="right"><b>Adelantos</b></TD>
<TD style="mso-number-format:'0.00';" align="right"><b>Saldo</b></TD>
<TD style="mso-number-format:'0.00';" align="right"><b>Facturado</b></TD>
</TR>
<?php   $unarray=$_SESSION["cc_arreglo"];
for($i=0;$i<count($unarray);$i++) {
printf("<tr>
<td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;( %s )</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td style=mso-number-format:0.00>%s</td>
<td style=mso-number-format:0.00>%s</td>
<td style=mso-number-format:0.00>%s</td>
<td style=mso-number-format:0.00>%s</td>
<td style=mso-number-format:0.00>%s</td>



</tr>", $unarray[$i]["ord_codigo"],
       $unarray[$i]["cli_nombre"],$unarray[$i]["sucursal"],
        $unarray[$i]["ord_descripcion"],
        tfecha($unarray[$i]["fecha_recepcion_ot"]),
       number_format(( $unarray[$i]["costo"]), 2,',',''),       
       number_format(( $unarray[$i]["costo_abono"]), 2,',',''),
       number_format(( $unarray[$i]["adelantos"]), 2,',',''),
       number_format(( $unarray[$i]["saldo_a"]), 2,',',''),
       number_format(( $unarray[$i]["compras"]), 2,',','')
        );
}
//mysql_free_result($result);
//mysql_close();  //Cierras la Conexión
unset($SESSION['cc_arreglo']);  
?>
</table>
</body>
</html>
?>
