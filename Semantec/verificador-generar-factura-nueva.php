<html> 
<?php

$cant=$_GET['cant'];
$cli_id=$_GET['cli_id'];
$cod_factura=$_GET['cod_factura'];
$fechaalta=$_GET['fechaalta'];  
$condicionventa=$_GET['condicionventa'];
$remito=$_GET['remito'];
$cantTotal=$_GET['cantTotal'];
$i=0;
$n=0;
?>
  <script type="text/javascript">
  function mandarFormulario() {
    //    alert('transfiriendo..');
	document.getElementById("formVerificadorOrdenes").submit();
  };
  </script>

    
<body onload="mandarFormulario()">
<form name="formVerificadorOrdenes" id="formVerificadorOrdenes" method="post" enctype="multipart/form-data" 
      action="ver-generar-factura-nueva.php?cli_id=<? echo $cli_id ?>&cod_factura=<? echo $cod_factura ?>&fechaalta=<? echo $fechaalta ?>&ocultar=no&condicionventa=<? echo $condicionventa ?>&remito=<? echo $remito ?>&cant=<? echo $cant ?>" >

<?php echo 'Cargando...';
while ($i < $cantTotal)
{ 
    $i++; //echo $i,'---';
    $CodigoOrden=$_POST["checkbox_ord_id$i"];
    if($CodigoOrden!=''){
        $n++;
    //    echo 'envio : ',$n,'>>>',$CodigoOrden,'<br>';
?>
       <input type="hidden" name="o<?php echo $n; ?>"  id="o<?php echo $n; ?>" value="<?php echo ($CodigoOrden); ?>" > 
<?  
    }

}
?>

</form>
</body>
</html> 