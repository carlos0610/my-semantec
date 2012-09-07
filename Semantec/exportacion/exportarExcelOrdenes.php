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
 $sql=$_GET["sql"]; 
$result=mysql_query($sql);
// INGRESAR NOMBRE 
$nombre=' Ordenes de Servicio';
?>
<b><big>SEMANTEC - </big>   <?php echo date("d-m-Y H:i:s"),'  ',$nombre; ?></b>

<TABLE BORDER=1 align="center" CELLPADDING=1 CELLSPACING=1>
<TR>
    
<TD><b>C&oacute;digo</b></TD>
<TD style="mso-number-format:'dd/mm/yyyy' "   ><b>Fecha</b></TD>
<TD><b>Cliente</b></TD>
<TD><b>Descripci&oacute;n</b></TD>
<TD><b>Proveedor</b></TD>
<TD><b>Estado</b></TD>
<TD style="mso-number-format:'0.00';"><b>Costo</b></TD>
<TD style="mso-number-format:'0.00';"><b>Venta</b></TD>

</TR>
<?php
while($row = mysql_fetch_array($result)) {
printf("<tr>
<td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>

</tr>", $row["ord_codigo"],
       tfecha($row["ord_alta"]),
        $row["cli_nombre"],
        $row["ord_descripcion"],
        $row["prv_nombre"],
        $row["est_nombre"],
        $row["ord_costo"],
        $row["ord_venta"]
        );
}
mysql_free_result($result);
mysql_close();  //Cierras la Conexión
?>
</table>
</body>
</html>
?>
