<?php
include("../validar.php");
include("../conexion.php"); 
include("../funciones.php");
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=CC-ClienteMovimientos.xls");
?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
</head>
<body>
<?php
// INGRESAR NOMBRE 
$nombre=' CC Ultimos Movimientos';
?>
<b><big>SEMANTEC - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center" CELLPADDING=1 CELLSPACING=1>
<TR bgcolor="#A9E2F3">

    
<TD><b>Cliente</b></TD>
<TD><b>Factura Nro</b></TD>
<TD><b>Nro Pago</b></TD>
<TD style="mso-number-format:'dd/mm/yyyy' "   ><b>Fecha de Pago</b></TD>
<TD style="mso-number-format:'0.00';" align="right"><b>Monto factura</b></TD>

</TR>
<?php   $unarray=$_SESSION["ccClienteovimientos"];
for($i=0;$i<count($unarray);$i++) {
printf("<tr>
<td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td style=mso-number-format:0.00 >%s</td>


</tr>", $unarray[$i]["cli_nombre"],
       $unarray[$i]["fav_codigo"],
        $unarray[$i]["cod_pago"],
        tfecha($unarray[$i]["fav_fecha_pago"]),
        number_format(($unarray[$i]["total"]), 2,',','')
        );
}
//mysql_free_result($result);
//mysql_close();  //Cierras la Conexión
unset($SESSION['ccClienteovimientos']);  
?>
</table>
</body>
</html>
?>
