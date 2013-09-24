<?php
include("../validar.php");
include("../conexion.php"); 
include("../funciones.php");
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Reportes.xls");
?>
<HTML LANG="es">
<TITLE>::. Exportacion de Datos .::</TITLE>
</head>
<body>
<?php

// INGRESAR NOMBRE 
$nombre=' Ordenes de Servicio';
?>
<b><big>SEMANTEC  - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center" CELLPADDING=1 CELLSPACING=1>
<TR>
    
<TD><b>Pago</b></TD>
<TD style="mso-number-format:'dd/mm/yyyy' "   ><b>Retención</b></TD>
<TD><b>Fecha</b></TD>
<TD><b>Prefijo</b></TD>
<TD><b>Nro</b></TD>
<TD><b>Importe</b></TD>
<!--
<TD style="mso-number-format:'0.00';"><b>Costo</b></TD>
<TD style="mso-number-format:'0.00';"><b>Venta</b></TD>
-->
</TR>
<?php
$arreglo = $_SESSION["retenciones_arreglo"];
for($i=0;$i<count($arreglo);$i++) {
printf("<tr>
<td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>



</tr>", $arreglo[$i]["id"],
        $arreglo[$i]["nombre"],
        $arreglo[$i]["ret_fecha"],
        $arreglo[$i]["ret_prefijo"],
        $arreglo[$i]["ret_codigo"],
        $arreglo[$i]["ret_importe"]
        );
}
//mysql_free_result($result);
mysql_close();  //Cierras la Conexión

?>
</table>
</body>
</html>
?>

