<head>
<?php
include("../validar.php");
include("../conexion.php"); 
include("../funciones.php");

//Datos
$dia=date("d-m-Y");
//Exportar datos de php a Excel
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Planilla_ReporteRetenciones_$dia.xls");


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

// INGRESAR NOMBRE 
$nombre=" Planilla de Reporte de retenciones";
?>
<b><big>SEMANTEC - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center"   scope="col" CELLPADDING=1 CELLSPACING=1 frame="above">
<TR bgcolor="#9C3B0E" class="encabezadoTabla">

    
<TD aling="center"><b>PAGO</b></TD>
<TD> <b>&nbsp; &nbsp; &nbsp; &nbsp; RETENCIÓN &nbsp; &nbsp; &nbsp; &nbsp;</b></TD>
<TD aling="center"><b>FECHA</b></TD>
<TD  scope="col" ><b>PREFIJO</b></TD>
<TD><b>NRO</b></TD>
<TD style="mso-number-format:'0.00';" ><b>IMPORTE</b></TD>



</TR>

<?php
$subtotal=0;
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
$subtotal += $arreglo[$i]["ret_importe"];
}
//mysql_free_result($result);
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
<b><? echo 'Total  $',(number_format($subtotal, 2, ',', '.')); ?></b>
</body>
</html>
?>

